<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de citas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .calendar-day, .calendar-day-head{
            border: 1px solid #ddd;
            padding: 20px;
        }
        .calendar-day-head{
            background: #ddd;
        }
        .calendar-wrapper {
            position: relative;
            float: left;
        }
        .calendar-buttons {
            position: absolute;
            bottom: -90px; /* Mover los botones hacia abajo */
            right: 0;
            display: flex; /* Usar flexbox */
            align-items: center; /* Alinear los elementos verticalmente */
            justify-content: flex-end; /* Alinear los elementos hacia el extremo derecho */
            margin: 5px;
            padding: 14%;
            padding-right: px;
        }

        .calendar-buttons button {
            margin-left: 5px; /* Espacio entre los botones */
        }
        .calendar-day.selected {
            background-color: black;
            color: white;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col">
            <!-- Controles para cambiar mes y año -->
            <div class="calendar-controls">
                <div class="calendar-month-buttons">
                    <button onclick="cambiarMes(-1)">&lt;</button>
                    <button onclick="cambiarMes(1)">&gt;</button>
                </div>
                <span id="current-month">Marzo</span>
                <span id="current-year">2024</span>
            </div>


            <!-- Calendario -->
            <div class="calendar-wrapper">
                <?php
                function generar_calendario($month, $year, $lang, $holidays = null) {
                    $calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';
                    if ($lang == 'en') {
                        $headings = array('M', 'T', 'W', 'T', 'F', 'S', 'S');
                    }
                    if ($lang == 'es') {
                        $headings = array('L', 'M', 'M', 'J', 'V', 'S', 'D');
                    }
                    if ($lang == 'ca') {
                        $headings = array('DI', 'Dm', 'Dc', 'Dj', 'Dv', 'Ds', 'Dg');
                    }
                    $calendar .= '<tr class="calendar-row"><td class="calendar-day-head">' . implode('</td><td class="calendar-day-head">', $headings) . '</td></tr>';
                    $running_day = date('w', mktime(0, 0, 0, $month, 1, $year));
                    $running_day = ($running_day > 0) ? $running_day - 1 : $running_day;
                    $days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));
                    $days_in_this_week = 1;
                    $day_counter = 0;
                    $dates_array = array();
                    $calendar .= '<tr class="calendar-row">';
                    for ($x = 0; $x < $running_day; $x++) :
                        $calendar .= '<td class="calendar-day-np"> </td>';
                        $days_in_this_week++;
                    endfor;
                    for ($list_day = 1; $list_day <= $days_in_month; $list_day++) :
                        $calendar .= '<td class="calendar-day">';
                        $class = "day-number ";
                        if ($running_day == 0 || $running_day == 6) {
                            $class .= " not-work ";
                        }
                        $key_month_day = "month_{$month}_day_{$list_day}";
                        if ($holidays != null && is_array($holidays)) {
                            $month_key = array_search($key_month_day, $holidays);
                            if (is_numeric($month_key)) {
                                $class .= " not-work-holiday ";
                            }
                        }
                        $calendar .= "<div class='{$class}' onclick='selectDay(this)'>" . $list_day . "</div>";
                        $calendar .= '</td>'; // Aquí había un error
                        if ($running_day == 6) :
                            $calendar .= '</tr>';
                            if (($day_counter + 1) != $days_in_month) :
                                $calendar .= '<tr class="calendar-row">';
                            endif;
                            $running_day = -1;
                            $days_in_this_week = 0;
                        endif;
                        $days_in_this_week++; $running_day++; $day_counter++;
                    endfor;
                    if ($days_in_this_week < 8) :
                        for ($x = 1; $x <= (8 - $days_in_this_week); $x++) :
                            $calendar .= '<td class="calendar-day-np"> </td>';
                        endfor;
                    endif;
                    $calendar .= '</tr>';
                    $calendar .= '</table>';
                    return $calendar;
                }
                echo generar_calendario(03, 2024, "es");
                ?>
            </div>

            <br>
            <br>
            <br>
            <br>

            <!-- Botones Aceptar y Cancelar -->
            
        </div>
    </div>
</div>

<!-- Formulario oculto para enviar la fecha seleccionada -->
<form id="fechaForm" action="guardar_fecha.php" method="post" style="display: none;">
    <input type="hidden" id="fechaSeleccionada" name="fechaSeleccionada">
    <input type="submit">
</form>

</body>
</html>
<script>
    var fechaSeleccionada = null;

    // JavaScript para cambiar el mes
    function cambiarMes(delta) {
        var currentMonth = document.getElementById("current-month");
        var currentYear = document.getElementById("current-year");
        var months = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        var monthIndex = months.indexOf(currentMonth.textContent);
        monthIndex += delta;
        if (monthIndex < 0) {
            monthIndex = 11;
            currentYear.textContent = parseInt(currentYear.textContent) - 1;
        } else if (monthIndex > 11) {
            monthIndex = 0;
            currentYear.textContent = parseInt(currentYear.textContent) + 1;
        }
        currentMonth.textContent = months[monthIndex];
    }

    // Función para seleccionar un día
    function selectDay(dayElement) {
        // Elimina la clase 'selected' de todos los días
        var allDays = document.querySelectorAll('.calendar-day');
        allDays.forEach(function(day) {
            day.classList.remove('selected');
        });

        // Agrega la clase 'selected' al día clickeado
        dayElement.parentNode.classList.add('selected');

        // Guarda la fecha seleccionada en la variable
        fechaSeleccionada = dayElement.textContent;
    }

    // Función para guardar la fecha seleccionada y enviarla al otro formulario
    function guardarFecha() {
        if (fechaSeleccionada !== null) {
            document.getElementById("fechaSeleccionada").value = fechaSeleccionada;
            document.getElementById("fechaForm").submit();
        } else {
            alert("Por favor, selecciona una fecha antes de aceptar.");
        }
    }

    // Función para cancelar la selección de fecha
    function cancelar() {
        fechaSeleccionada = null;
        var selectedDay = document.querySelector('.calendar-day.selected');
        if (selectedDay !== null) {
            selectedDay.classList.remove('selected');
        }
    }
</script>
