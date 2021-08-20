<html>
    <head>
        <title>IoT Alarm</title>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>

        <script>
            function changeAlarm()
            {
                var id = $(this).attr('name');
                $.ajax({
                    url  : './php/api/alarm.php',
                    data : {
                        _method : 'PUT',
                        id      : id,
                        name    : $('input.device-name[name=' + id + ']').val(),
                        location : $('input.device-loc[name=' + id + ']').val()
                    },
                    type : 'POST'
                });
            }

            $(document).ready(function () {
                $('#isSelected').bind('change', function () {
                    $.ajax({
                        url  : './php/api/alarm_ring.php',
                        data : {
                            alarm_ring : $(this).is(':checked')
                        },
                        type : 'POST'
                    });
                });

                $.ajax({
                    url  : './php/api/alarm_ring.php',
                    type : 'GET',
                    success : function (data) {
                        $('#isSelected').prop('checked', data);
                    }
                });

                $('#btnClear').click(function () {
                    if (confirm('This will delete all recorded alerts. Are you sure?')) {
                        $.ajax({
                            url  : './php/api/add_alert.php',
                            data : {
                                _method : 'DELETE'
                            },
                            type : 'POST'
                        });

                        location.reload();
                    }
                });

                $(document).on('blur', 'input.device-name', changeAlarm);
                $(document).on('blur', 'input.device-loc', changeAlarm);

                $('#alertTable').DataTable();
            });
        </script>
    </head>

    <body>
        <?php
            require_once($_SERVER['DOCUMENT_ROOT'] . '/php/controllers/alert_controller.php');
            require_once($_SERVER['DOCUMENT_ROOT'] . '/php/controllers/alarm_controller.php');

            $alertController = new \App\Controllers\AlertController();
            $alarmController = new \App\Controllers\AlarmController();
        ?>

        <nav>
        </nav>

        <main>
            <div class="d-flex justify-content-center">
                <h1 class="display-1">House Alarm</h1>
            </div>

            <div class="pt-5 pl-5 pr-5">
                <button type="button" class="btn btn-outline-secondary btn-block btn-lg" onClick="window.location.reload();">Refresh</button>
                <button type="button" class="btn btn-outline-danger btn-block btn-sm" id="btnClear">Clear Alerts</button>
            <div>

            <hr>

            <div class="container-fluid pt-3 pb-4">
                <div class="row">

                    <div class="col-6 pl-1 pr-4">
                        <div>
                            <h2>Alerts</h2>
                        </div>

                        <table id="alertTable" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Alarm date</th>
                                    <th>Alarm time</th>
                                    <th>Alarm ID</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                    $data = $alertController->Get();
                                    foreach (array_reverse($data) as $alert) {
                                        echo '<tr>';
                                            echo '<td>';
                                            echo $alert->attributes['date'];
                                            echo '</td>';

                                            echo '<td>';
                                            echo $alert->attributes['time'];
                                            echo '</td>';

                                            echo '<td>';
                                            echo $alert->attributes['alarm_id'];
                                            echo '</td>';
                                        echo '</tr>';
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-6 pl-4 pr-1">
                        <div>
                            <h2>Devices</h2>
                        </div>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Alarm ID</th>
                                    <th>Name</th>
                                    <th>Location</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                    $data = $alarmController->Get();
                                    foreach (array_reverse($data) as $alarm) {
                                        $alarm_id = $alarm->attributes['id'];
                                        $alarm_name = $alarm->attributes['name'];
                                        $alarm_location = $alarm->attributes['location'];

                                        echo '<tr>';
                                            echo '<td>';
                                            echo $alarm_id;
                                            echo '</td>';

                                            echo '<td>';
                                            echo '<input type="text" class="device-name" name="'.$alarm_id.'" value="'.$alarm_name.'">';
                                            echo '</td>';

                                            echo '<td>';
                                            echo '<input type="text" class="device-loc" name="'.$alarm_id.'" value="'.$alarm_location.'">';
                                            echo '</td>';
                                        echo '</tr>';
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <hr>

            <div class="pb-5">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="isSelected">
                    <label class="custom-control-label" for="isSelected">Sound alarm on alert</label>
                </div>
            </div>
        </main> 

    </body>
</html>