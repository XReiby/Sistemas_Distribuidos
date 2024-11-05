document.addEventListener('DOMContentLoaded', function () {
    fetchCurrentLeader();
    setInterval(fetchMessages, 2000);
    setInterval(fetchCurrentLeader, 5000);

    function fetchMessages() {
        fetch('/api/messages')
            .then(response => response.json())
            .then(data => {
                const messageList = document.getElementById('message-list');
                messageList.innerHTML = '';

                data.forEach(message => {
                    const li = document.createElement('li');
                    li.textContent = `[${message.sender_node}] (${message.type}): ${message.message}`;
                    messageList.appendChild(li);
                });
            });
    }

    function fetchCurrentLeader() {
        fetch('/api/current-leader')
            .then(response => response.json())
            .then(data => {
                document.getElementById('current-leader').textContent = data.current_leader;
            });
    }
});
