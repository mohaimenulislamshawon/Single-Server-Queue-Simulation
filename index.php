<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queue Simulation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 10px;
            color: #333;
        }
        input[type="text"],
        input[type="number"] {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            padding: 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Queue Simulation</h2>
        <form method="post" action="">
            <label for="arrival_probs">Enter the probabilities for time between arrivals (separated by spaces):</label>
            <input type="text" id="arrival_probs" name="arrival_probs" required>

            <label for="service_probs">Enter the probabilities for service times (separated by spaces):</label>
            <input type="text" id="service_probs" name="service_probs" required>

            <label for="arrival_digits">Enter the random digits for time between arrivals (separated by spaces):</label>
            <input type="text" id="arrival_digits" name="arrival_digits" required>

            <label for="service_digits">Enter the random digits for service times (separated by spaces):</label>
            <input type="text" id="service_digits" name="service_digits" required>

            <label for="num_customers">Enter the number of customers to simulate:</label>
            <input type="number" id="num_customers" name="num_customers" required>

            <button type="submit">Simulate</button>
        </form>

        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            <table>
                <thead>
                    <tr>
                        <th>Customer No.</th>
                        <th>IAT</th>
                        <th>Arrival Time</th>
                        <th>Service Time</th>
                        <th>Time Service Begin</th>
                        <th>Waiting Time</th>
                        <th>Time Service End</th>
                        <th>Time Spent in System</th>
                        <th>Idle Time of Server</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        function get_time_between_arrival($digit, $time_between_arrival_probs) {
                            $cumulative_prob = 0;
                            foreach ($time_between_arrival_probs as $i => $prob) {
                                $cumulative_prob += $prob;
                                if ($digit / 1000 < $cumulative_prob) {
                                    return $i + 1;
                                }
                            }
                            return count($time_between_arrival_probs);
                        }

                        function get_service_time($digit, $service_time_probs) {
                            $cumulative_prob = 0;
                            foreach ($service_time_probs as $time => $prob) {
                                $cumulative_prob += $prob;
                                if ($digit / 100 <= $cumulative_prob) {
                                    return $time;
                                }
                            }
                            return max(array_keys($service_time_probs));
                        }

                        function simulate_queue($num_customers, $time_between_arrival_probs, $service_time_probs, $arrival_digits, $service_digits) {
                            $results = [];
                            $current_time = 0;
                            $server_free_time = 0;

                            for ($i = 0; $i < $num_customers; $i++) {
                                $customer_no = $i + 1;
                                if ($i == 0) {
                                    $iat = 0; // Set IAT to 0 for the first customer
                                } else {
                                    $iat = get_time_between_arrival($arrival_digits[$i], $time_between_arrival_probs);
                                }

                                if ($i == 0) {
                                    $arrival_time = 0; // First customer arrives at time 0
                                } else {
                                    $arrival_time = $results[$i - 1]['Arrival Time'] + $iat;
                                }

                                $service_time = get_service_time($service_digits[$i], $service_time_probs);

                                if ($arrival_time < $server_free_time) {
                                    $time_service_begin = $server_free_time;
                                    $waiting_time = $server_free_time - $arrival_time;
                                } else {
                                    $time_service_begin = $arrival_time;
                                    $waiting_time = 0;
                                }

                                $time_service_end = $time_service_begin + $service_time;
                                $time_spent_in_system = $time_service_end - $arrival_time;

                                if ($i > 0) {
                                    $idle_time = max(0, $time_service_begin - $results[$i - 1]['Time service End']);
                                } else {
                                    $idle_time = 0; // No idle time before the first customer
                                }

                                $results[] = [
                                    'Customer No.' => $customer_no,
                                    'IAT' => $iat,
                                    'Arrival Time' => $arrival_time,
                                    'Service Time' => $service_time,
                                    'Time service Begin' => $time_service_begin,
                                    'Waiting Time' => $waiting_time,
                                    'Time service End' => $time_service_end,
                                    'Time spent in system' => $time_spent_in_system,
                                    'Idle time of server' => $idle_time
                                ];

                                $server_free_time = $time_service_end;
                            }

                            return $results;
                        }

                        // Input data from user
                        $time_between_arrival_probs = array_map('floatval', explode(' ', $_POST['arrival_probs']));
                        $service_time_probs_input = array_map('floatval', explode(' ', $_POST['service_probs']));
                        $service_time_probs = [];
                        foreach ($service_time_probs_input as $i => $prob) {
                            $service_time_probs[$i + 1] = $prob;
                        }
                        $arrival_digits = array_map('intval', explode(' ', $_POST['arrival_digits']));
                        $service_digits = array_map('intval', explode(' ', $_POST['service_digits']));
                        $num_customers = intval($_POST['num_customers']);

                        // Perform the simulation
                        $simulation_results = simulate_queue($num_customers, $time_between_arrival_probs, $service_time_probs, $arrival_digits, $service_digits);

                        // Print the results in a formatted table
                        foreach ($simulation_results as $result) {
                            echo "<tr>";
                            echo "<td>{$result['Customer No.']}</td>";
                            echo "<td>{$result['IAT']}</td>";
                            echo "<td>" . number_format($result['Arrival Time'], 2) . "</td>";
                            echo "<td>{$result['Service Time']}</td>";
                            echo "<td>" . number_format($result['Time service Begin'], 2) . "</td>";
                            echo "<td>" . number_format($result['Waiting Time'], 2) . "</td>";
                            echo "<td>" . number_format($result['Time service End'], 2) . "</td>";
                            echo "<td>" . number_format($result['Time spent in system'], 2) . "</td>";
                            echo "<td>" . number_format($result['Idle time of server'], 2) . "</td>";
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
