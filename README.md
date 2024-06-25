# Single Server Queue Simulation in PHP

This repository contains a single server queue simulation implemented in PHP, complete with a user-friendly interface using HTML and CSS. The simulation models the behavior of customers arriving at a service point, where they may have to wait if the server is busy, and their interactions with the server.

**Take a View:** http://1serversimulation.unaux.com/

## Features

- **User Inputs**: Allows users to input probabilities for time between arrivals and service times, random digits for both arrival times and service times, and the number of customers to simulate.
- **Dynamic Simulation**: Based on user inputs, the simulation calculates various parameters such as inter-arrival time, arrival time, service time, waiting time, service begin time, service end time, time spent in the system, and server idle time.
- **Results Display**: Outputs the simulation results in a neatly formatted HTML table for easy analysis.

## How to Use

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/your-username/queue-simulation-php.git
   ```
2. **Navigate to the Directory**:
   ```bash
   cd queue-simulation-php
   ```
3. **Set Up Your PHP Environment**: Ensure you have a local PHP server set up. You can use tools like XAMPP, WAMP, or MAMP, or use PHP's built-in server:
   ```bash
   php -S localhost:8000
   ```
4. **Open in Browser**: Open your web browser and navigate to `http://localhost:8000/index.php`.

## User Guide

- **Probabilities for Time Between Arrivals**: Enter the probabilities as space-separated values. These should sum to 1.
- **Probabilities for Service Times**: Enter the probabilities as space-separated values corresponding to service times from 1 to N. These should also sum to 1.
- **Random Digits for Time Between Arrivals**: Enter the random digits as space-separated values.
- **Random Digits for Service Times**: Enter the random digits as space-separated values.
- **Number of Customers**: Enter the number of customers to simulate.

## Example

For example, if you want to simulate 6 customers with specific probabilities and random digits, you would enter:

- **Probabilities for Time Between Arrivals**: `0.125 0.125 0.125 0.125 0.125 0.125 0.125 0.125`
- **Probabilities for Service Times**: `0.3 0.2 0.1 0.05 0.1 0.25`
- **Random Digits for Time Between Arrivals**: `0 927 525 870 52 105`
- **Random Digits for Service Times**: `80 25 92 5 42 65`
- **Number of Customers**: `6`

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Contributing

Contributions are welcome! Please fork this repository, make your changes, and submit a pull request.
