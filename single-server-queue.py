import random

def get_user_input():
    print("Enter the probabilities for time between arrivals (separated by spaces, each between 0 and 1, summing to 1):")
    time_between_arrival_probs = list(map(float, input().split()))
    
    print("\nEnter the probabilities for service times (for each time separated by spaces, each between 0 and 1, summing to 1):")
    service_time_probs_input = list(map(float, input().split()))
    service_time_probs = {i + 1: prob for i, prob in enumerate(service_time_probs_input)}
    
    print("\nEnter the random digits for time between arrivals (separated by spaces):")
    arrival_digits = list(map(int, input().split()))
    
    print("\nEnter the random digits for service times (separated by spaces):")
    service_digits = list(map(int, input().split()))
    
    print("\nEnter the number of customers to simulate:")
    num_customers = int(input())
    
    return time_between_arrival_probs, service_time_probs, arrival_digits, service_digits, num_customers

def get_time_between_arrival(digit, time_between_arrival_probs):
    cumulative_prob = 0
    for i, prob in enumerate(time_between_arrival_probs, start=1):
        cumulative_prob += prob
        if digit / 1000 < cumulative_prob:
            return i
    return len(time_between_arrival_probs)

def get_service_time(digit, service_time_probs):
    cumulative_prob = 0
    for time, prob in service_time_probs.items():
        cumulative_prob += prob
        if digit / 100 <= cumulative_prob:
            return time
    return max(service_time_probs.keys())

def simulate_queue(num_customers, time_between_arrival_probs, service_time_probs, arrival_digits, service_digits):
    results = []
    current_time = 0
    server_free_time = 0

    for i in range(num_customers):
        customer_no = i + 1
        if i == 0:
            iat = 0  # Set IAT to 0 for the first customer
        else:
            iat = get_time_between_arrival(arrival_digits[i], time_between_arrival_probs)
        
        if i == 0:
            arrival_time = 0  # First customer arrives at time 0
        else:
            arrival_time = results[-1]['Arrival Time'] + iat
        
        service_time = get_service_time(service_digits[i], service_time_probs)
        
        if arrival_time < server_free_time:
            time_service_begin = server_free_time
            waiting_time = server_free_time - arrival_time
        else:
            time_service_begin = arrival_time
            waiting_time = 0
        
        time_service_end = time_service_begin + service_time
        time_spent_in_system = time_service_end - arrival_time
        
        if i > 0:
            idle_time = max(0, time_service_begin - results[-1]['Time service End'])
        else:
            idle_time = 0  # No idle time before the first customer
        
        results.append({
            'Customer No.': customer_no,
            'IAT': iat,
            'Arrival Time': arrival_time,
            'Service Time': service_time,
            'Time service Begin': time_service_begin,
            'Waiting Time': waiting_time,
            'Time service End': time_service_end,
            'Time spent in system': time_spent_in_system,
            'Idle time of server': idle_time
        })
        
        server_free_time = time_service_end

    return results

def print_simulation_results(results):
    print(f"{'Customer No.':<15}{'IAT':<8}{'Arrival Time':<15}{'Service Time':<15}{'Time service Begin':<20}{'Waiting Time':<15}{'Time service End':<20}{'Time spent in system':<25}{'Idle time of server':<20}")
    print("-" * 150)

    for result in results:
        print(f"{result['Customer No.']:<15}{result['IAT']:<8}{result['Arrival Time']:<15.2f}{result['Service Time']:<15}{result['Time service Begin']:<20.2f}{result['Waiting Time']:<15.2f}{result['Time service End']:<20.2f}{result['Time spent in system']:<25.2f}{result['Idle time of server']:<20.2f}")

# Main execution
time_between_arrival_probs, service_time_probs, arrival_digits, service_digits, num_customers = get_user_input()
simulation_results = simulate_queue(num_customers, time_between_arrival_probs, service_time_probs, arrival_digits, service_digits)
print_simulation_results(simulation_results)
