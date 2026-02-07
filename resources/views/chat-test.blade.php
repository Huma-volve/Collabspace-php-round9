<!DOCTYPE html>
<html>
<head>
    <title>WebSocket Test</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Real-time Chat Test</h1>
            <div id="user-info" class="text-sm text-gray-600"></div>
        </div>
        
        <!-- Connection Status -->
        <div id="status" class="p-3 mb-4 rounded-lg bg-yellow-100 text-yellow-700 font-medium">
            ⏳ Initializing...
        </div>
        
        <!-- Auth Error -->
        <div id="auth-error" class="hidden p-3 mb-4 rounded-lg bg-red-100 text-red-700"></div>
        
        <!-- Messages Container -->
        <div id="messages" class="h-96 overflow-y-auto p-4 mb-4 bg-white rounded-lg shadow border border-gray-200 flex flex-col gap-3"></div>
        
        <!-- Typing Indicator -->
        <div id="typing-indicator" class="hidden p-2 mb-2 text-gray-500 text-sm italic">
            <span id="typing-text"></span>
        </div>
        
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
        // Get token and user info from URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const authToken = urlParams.get('token');
        const userId = urlParams.get('user_id');
        const userName = urlParams.get('user_name');
        console.log(authToken, "authToken", userId, "userId", userName, "userName");

        // Set global auth token for Echo
        window.authToken = authToken;

        const statusDiv = document.getElementById('status');
        const messagesDiv = document.getElementById('messages');
        const form = document.getElementById('form');
        const input = document.getElementById('input');
        const userInfoDiv = document.getElementById('user-info');
        const authErrorDiv = document.getElementById('auth-error');

        // Check if authenticated
        if (!authToken || !userId) {
            authErrorDiv.textContent = '❌ Not authenticated. Please login with Google first.';
            authErrorDiv.classList.remove('hidden');
            statusDiv.textContent = '⛔ Authentication required';
            statusDiv.className = 'p-3 mb-4 rounded-lg bg-red-100 text-red-700 font-medium';
            form.style.display = 'none';
            
            // Redirect to google-test after 3 seconds
            setTimeout(() => {
                window.location.href = '/google-test';
            }, 1000);
        } else {
            userInfoDiv.textContent = `Logged in as: ${userName} (ID: ${userId})`;
        }

        const CHAT_ID = 1;         
        const USER_ID = parseInt(userId);
        
        // Cursor Pagination state
        let nextCursor = null;
        let hasMore = false;
        let isLoading = false;
        let isInitialLoad = true;

        function updateStatus(connected) {
            if (connected) {
                statusDiv.className = 'p-3 mb-4 rounded-lg bg-green-100 text-green-700 font-medium transition-colors duration-300';
                statusDiv.textContent = '✅ Connected';
            } else {
                statusDiv.className = 'p-3 mb-4 rounded-lg bg-red-100 text-red-700 font-medium transition-colors duration-300';
                statusDiv.textContent = '❌ Disconnected';
            }
        }
        
        // Monitor connection state
        window.Echo.connector.pusher.connection.bind('connected', () => {
            console.log('✅ Connected to WebSocket');
            updateStatus(true);
        });

        window.Echo.connector.pusher.connection.bind('disconnected', () => {
            console.log('❌ Disconnected from WebSocket');
            updateStatus(false);
        });

        window.Echo.connector.pusher.connection.bind('error', (err) => {
            console.error('❌ Connection error:', err);
            updateStatus(false);
        });

        console.log('🔌 Connecting to WebSocket...');

        // Function: Add message to screen
        function addMessage(msg, isOwn, prepend = false) {
            const div = document.createElement('div');
            
            const baseClasses = 'max-w-[80%] p-3 rounded-lg shadow-sm';
            const ownClasses = 'bg-blue-100 self-end text-right';
            const otherClasses = 'bg-gray-50 self-start text-left border border-gray-200';
            
            div.className = `${baseClasses} ${isOwn ? ownClasses : otherClasses}`;
            
            const time = new Date(msg.created_at).toLocaleTimeString();
            
            div.innerHTML = `
                <div class="font-bold text-sm mb-1 ${isOwn ? 'text-blue-800' : 'text-gray-800'}">${msg.sender.full_name || msg.sender.name}</div>
                <div class="text-gray-800">${msg.body}</div>
                <div class="text-xs text-gray-500 mt-1">${time}</div>
            `;
            
            if (prepend) {
                messagesDiv.insertBefore(div, messagesDiv.firstChild);
            } else {
                messagesDiv.appendChild(div);
            }
            
            if (!prepend) {
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            }
        }

        // Typing indicator state
        let typingTimer;
        const typingDelay = 1000;
        let isTyping = false;
        const typingIndicator = document.getElementById('typing-indicator');
        const typingText = document.getElementById('typing-text');
        const typingUsers = new Map();

        // Function: Send typing status
        async function sendTypingStatus(typing) {
            if (!authToken) return;
            
            try {
                await fetch(`/api/chats/${CHAT_ID}/typing`, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${authToken}`
                    },
                    body: JSON.stringify({ is_typing: typing })
                });
            } catch (error) {
                console.error('❌ Failed to send typing status:', error);
            }
        }

        // Function: Update typing indicator display
        function updateTypingIndicator() {
            if (typingUsers.size === 0) {
                typingIndicator.classList.add('hidden');
                return;
            }
            
            const names = Array.from(typingUsers.values());
            let text = '';
            
            if (names.length === 1) {
                text = `${names[0]} is typing...`;
            } else if (names.length === 2) {
                text = `${names[0]} and ${names[1]} are typing...`;
            } else {
                text = `${names[0]} and ${names.length - 1} others are typing...`;
            }
            
            typingText.textContent = text;
            typingIndicator.classList.remove('hidden');
        }

        // Subscribe to PRIVATE chat channel
        if (authToken) {
            window.Echo.private(`chat.${CHAT_ID}`)
                .listen('.message.sent', (e) => {
                    console.log('📨 Message received:', e.message);
                    
                    // Skip if this is our own message (defensive check, backend uses toOthers())
                    if (e.message.sender.id === USER_ID) {
                        console.log('⚠️ Skipping own message from broadcast (should not happen with toOthers)');
                        return;
                    }
                    
                    addMessage(e.message, false);
                })
                .listen('.user.typing', (e) => { 
                    console.log('👀 Typing event:', e);
                    
                    if (e.user.id === USER_ID) return; // Don't show our own typing
                    
                    if (e.is_typing) {
                        typingUsers.set(e.user.id, e.user.name);
                        
                        setTimeout(() => {
                            typingUsers.delete(e.user.id);
                            updateTypingIndicator();
                        }, 3000);
                    } else {
                        typingUsers.delete(e.user.id);
                    }
                    
                    updateTypingIndicator();
                })
                .error((error) => {
                    console.error('❌ Channel subscription error:', error);
                    updateStatus(false);
                    
                    if (error.type === 'AuthError') {
                        authErrorDiv.textContent = '❌ Authentication failed for private channel. Token may be invalid.';
                        authErrorDiv.classList.remove('hidden');
                    }
                });
        }

        // Handle input typing
        input.addEventListener('input', () => {
            if (!authToken) return;
            
            clearTimeout(typingTimer);
            
            if (!isTyping && input.value.trim()) {
                isTyping = true;
                sendTypingStatus(true);
            }
            
            typingTimer = setTimeout(() => {
                if (isTyping) {
                    isTyping = false;
                    sendTypingStatus(false);
                }
            }, typingDelay);
        });

        // Send new message
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            if (!authToken) {
                alert('You must be authenticated to send messages');
                return;
            }
            
            const body = input.value.trim();
            if (!body) return;

            try {
                const response = await fetch(`/api/chats/${CHAT_ID}/messages`, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${authToken}`
                    },
                    body: JSON.stringify({ body })
                });

                const data = await response.json();

                if (data.success) {
                    console.log('✅ Message sent');
                    addMessage(data.data, true);
                    input.value = '';
                    
                    if (isTyping) {
                        isTyping = false;
                        sendTypingStatus(false);
                    }
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('❌ Network error:', error);
                alert('Failed to send message');
            }
        });

        // Load messages (with cursor pagination)
        async function loadMessages(cursor = null) {
            if (!authToken || isLoading) return;
            
            isLoading = true;
            try {
                let url = `/api/chats/${CHAT_ID}/messages`;
                if (cursor) {
                    url += `?cursor=${encodeURIComponent(cursor)}`;
                }
                
                const response = await fetch(url, {
                    headers: {
                        'Authorization': `Bearer ${authToken}`
                    }
                });
                const result = await response.json();
                
                if (result.success && result.data && result.data.length > 0) {
                    if (result.pagination) {
                        nextCursor = result.pagination.next_cursor;
                        hasMore = result.pagination.has_more;
                        console.log(`📄 Loaded ${result.data.length} messages, has_more: ${hasMore}`);
                    }
                    
                    if (isInitialLoad) {
                        const messages = result.data.reverse();
                        messages.forEach(msg => {
                            addMessage(msg, msg.sender.id === USER_ID);
                        });
                        isInitialLoad = false;
                    } else {
                        const oldScrollHeight = messagesDiv.scrollHeight;
                        
                        result.data.forEach(msg => {
                            addMessage(msg, msg.sender.id === USER_ID, true);
                        });
                        
                        messagesDiv.scrollTop = messagesDiv.scrollHeight - oldScrollHeight;
                    }
                }
            } catch (error) {
                console.error('❌ Failed to load messages:', error);
            } finally {
                isLoading = false;
            }
        }

        messagesDiv.addEventListener('scroll', () => {
            if (messagesDiv.scrollTop === 0 && hasMore && !isLoading) {
                console.log('🔄 Loading more messages with cursor:', nextCursor);
                loadMessages(nextCursor);
            }
        });

        // Load initial messages if authenticated
        if (authToken) {
            loadMessages();
        }
    </script>
</body>
</html>
