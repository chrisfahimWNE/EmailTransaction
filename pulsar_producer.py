import pulsar
import json

# Define the Pulsar service URL and topic
pulsar_service_url = 'pulsar://localhost:6650'  # Adjust if needed
topic = 'persistent://public/default/build-email'    # Adjust to your topic

# Create a Pulsar client
client = pulsar.Client(pulsar_service_url)

# Create a producer for the specified topic
producer = client.create_producer(topic)

message = {
    "name": "Tester",
    "email": "cfahim@gmail.com",
    "type": "confirm-email",
    "version": "prod"
}
json_message = json.dumps(message)
producer.send(json_message.encode('utf-8'))  # Send message as bytes
print(f'Sent: {json_message}')

# Close the producer and client
producer.close()
client.close()