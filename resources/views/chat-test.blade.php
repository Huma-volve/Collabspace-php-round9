<!DOCTYPE html>
<html>
<head>
    <title>WebSocket Test</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { 
            font-family: Arial; 
            max-width: 600px; 
            margin: 50px auto; 
            padding: 20px;
        }
        #status { 
            padding: 10px; 
            margin-bottom: 20px; 
            border-radius: 5px;
        }
        .connected { background: #d4edda; color: #155724; }
        .disconnected { background: #f8d7da; color: #721c24; }
        
        #messages { 
            border: 1px solid #ddd; 
            height: 300px; 
            overflow-y: auto; 
            padding: 10px; 
            margin-bottom: 20px;
            background: #f9f9f9;
        }
        .message { 
            padding: 8px; 
            margin: 5px 0; 
            background: white; 
            border-radius: 5px;
        }
        .own { 
            background: #e3f2fd; 
            text-align: right;
        }
        input { 
            width: 70%; 
            padding: 10px; 
            border: 1px solid #ddd;
        }
        button { 
            padding: 10px 20px; 
            background: #007bff; 
            color: white; 
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Real-time Chat Test</h1>
    
    <!-- Connection Status -->
    <div id="status" class="disconnected">❌ Disconnected</div>
    
    <!-- Messages Container -->
    <div id="messages"></div>
    
    <!-- Send Form -->
    <form id="form">
        <input type="text" id="input" placeholder="Type message..." required>
        <button type="submit">Send</button>
    </form>

    <script type="module">

        const CHAT_ID = 1;         
        const USER_ID = 2;         
        
        const statusDiv = document.getElementById('status');
        const messagesDiv = document.getElementById('messages');
        const form = document.getElementById('form');
        const input = document.getElementById('input');


        function updateStatus(connected) {
            if (connected) {
                statusDiv.className = 'connected';
                statusDiv.textContent = 'Connected';
            } else {
                statusDiv.className = 'disconnected';
                statusDiv.textContent = 'Disconnected';
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
            div.className = 'message' + (isOwn ? ' own' : '');
            
            const time = new Date(msg.created_at).toLocaleTimeString();
            div.innerHTML = `
                <strong>${msg.sender.full_name}</strong><br>
                ${msg.body}<br>
                <small style="color: #666">${time}</small>
            `;
            
            messagesDiv.appendChild(div);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        
        window.Echo.channel(`chat.${CHAT_ID}`)
            .listen('.message.sent', (e) => {
                console.log('📨 Message received:', e.message);
                
                // تجاهل الرسائل اللي بعتها انت (عشان toOthers مش شغال مع public channel)
                if (e.message.user_id === USER_ID) {
                    console.log('⏭️ Ignoring own message');
                    return;
                }
                
                addMessage(e.message, false);
            })
            .error((error) => {
              console.log('Failed to connect to WebSocket');
              console.error('❌ Error:', error); // بيظهر error هنا 
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

        // ========================================
        // تحميل الرسائل القديمة
        // ========================================
        async function loadMessages() {
            try {
                const response = await fetch(`/api/chats/${CHAT_ID}/messages`);
                const data = await response.json();

                if (data.success && data.data.length > 0) {
                    data.data.forEach(msg => {
                        addMessage(msg, msg.user_id === USER_ID);
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
