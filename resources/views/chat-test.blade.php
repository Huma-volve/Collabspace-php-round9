<!DOCTYPE html>
<html>
<head>
    <title>WebSocket Test</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Real-time Chat Test</h1>
        
        <!-- Connection Status -->
        <div id="status" class="p-3 mb-4 rounded-lg bg-red-100 text-red-700 font-medium">
            ❌ Disconnected
        </div>
        
        <!-- Messages Container -->
        <div id="messages" class="h-96 overflow-y-auto p-4 mb-4 bg-white rounded-lg shadow border border-gray-200 flex flex-col gap-3"></div>
        
        <!-- Send Form -->
        <form id="form" class="flex gap-2">
            <input type="text" id="input" placeholder="Type message..." required 
                   class="flex-1 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" 
                    class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                Send
            </button>
        </form>
    </div>

    <script type="module">

        const CHAT_ID = 1;         
        const USER_ID = 1;         
        
        const statusDiv = document.getElementById('status');
        const messagesDiv = document.getElementById('messages');
        const form = document.getElementById('form');
        const input = document.getElementById('input');


        function updateStatus(connected) {
            if (connected) {
                statusDiv.className = 'p-3 mb-4 rounded-lg bg-green-100 text-green-700 font-medium transition-colors duration-300';
                statusDiv.textContent = '✅ Connected';
            } else {
                statusDiv.className = 'p-3 mb-4 rounded-lg bg-red-100 text-red-700 font-medium transition-colors duration-300';
                statusDiv.textContent = '❌ Disconnected';
            }
        }
        
        // مراقبة حالة الاتصال
        window.Echo.connector.pusher.connection.bind('connected', () => {
            console.log('Connected');
            updateStatus(true);
        });

        window.Echo.connector.pusher.connection.bind('disconnected', () => {
            console.log('Disconnected');
            updateStatus(false);
        });
        console.log('🔌 Connecting to WebSocket...');



        // دالة: إضافة رسالة للشاشة
        function addMessage(msg, isOwn) {
            const div = document.createElement('div');
            
            // Base classes
            const baseClasses = 'max-w-[80%] p-3 rounded-lg shadow-sm';
            // Specific classes based on sender
            const ownClasses = 'bg-blue-100 self-end text-right';
            const otherClasses = 'bg-gray-50 self-start text-left border border-gray-200';
            
            div.className = `${baseClasses} ${isOwn ? ownClasses : otherClasses}`;
            
            const time = new Date(msg.created_at).toLocaleTimeString();
            
            // Message content
            div.innerHTML = `
                <div class="font-bold text-sm mb-1 ${isOwn ? 'text-blue-800' : 'text-gray-800'}">${msg.sender.full_name}</div>
                <div class="text-gray-800">${msg.body}</div>
                <div class="text-xs text-gray-500 mt-1">${time}</div>
            `;
            
            messagesDiv.appendChild(div);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        
        window.Echo.channel(`chat.${CHAT_ID}`)
            .listen('.message.sent', (e) => {
                console.log('📨 Message received:', e.message);
                
                addMessage(e.message, false);
            })
            .error((error) => {
                console.log('Failed to connect to WebSocket');
                console.error('❌ Error:', error);
                updateStatus(false); 
            });

        // إرسال رسالة جديدة
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const body = input.value.trim();
            if (!body) return;

            try {
                // إرسال الـ request للـ API
                const response = await fetch(`/api/chats/${CHAT_ID}/messages`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ body })
                });

                const data = await response.json();

                if (data.success) {
                    console.log('✅ Message sent');
                    addMessage(data.data, true);
                    input.value = '';
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('❌ Network error:', error);
                alert('Failed to send message');
            }
        });

        // تحميل الرسائل القديمة
        async function loadMessages() {
            try {
                const response = await fetch(`/api/chats/${CHAT_ID}/messages`);
                const data = await response.json();

                if (data.success && data.data.length > 0) {
                    data.data.forEach(msg => {
                        addMessage(msg, msg.sender.id === USER_ID);
                    });
                }
            } catch (error) {
                console.error('❌ Failed to load messages:', error);
            }
        }

        loadMessages();
    </script>
</body>
</html>
