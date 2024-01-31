<template>
    <div>
        <div>
            <h2>Online Users</h2>
            <ul>
                <li style="margin-bottom: 5px;" v-for="(user, index) in onlineUsers" :key="index"
                    @click="openDialog(user)">
                    <button style="border-radius: 10px">{{ user.name }}</button>
                    <span v-if="user.newMessagesCount > 0">{{ user.newMessagesCount }}</span>
                </li>
            </ul>
        </div>

        <div v-if="currentDialog">
            <h2>{{ currentDialog.name }}</h2>
            <div>
                <div v-for="(message, index) in currentDialog.messages" :key="index">
                    <strong>{{ message.name ?? onlineUsers.find(o => o.id === message.sender_id).name }}:</strong> {{ message.message }}
                </div>
            </div>
            <div class="chat-input">
                <input v-model="newMessage" placeholder="Type your message..."/>
                <button @click="sendMessage">Send</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['currentUser'],
    data() {
        return {
            onlineUsers: [],
            currentDialog: null,
            newMessage: '',
        };
    },
    mounted() {

    },
    created() {
        window.Echo.encryptedPrivate('chat-message' + this.currentUser.id)
            .listen('ChatMessage', (event) => {
                console.log('NEW MESSAGE ARRIVE ' + this.currentUser.id);
                if (this.currentDialog && this.currentDialog.id === event.message.senderId) {
                    this.currentDialog.messages.push(event.message);
                } else {
                    const userIndex = this.onlineUsers.findIndex(user => user.id === event.message.senderId);
                    if (userIndex !== -1) {
                        console.log(this.onlineUsers[userIndex].newMessagesCount);
                        const newMessagesCount = this.onlineUsers[userIndex]?.newMessagesCount ?? 0;
                        const updatedNewMessagesCount = newMessagesCount + 1;
                        this.onlineUsers[userIndex] = {
                            ...this.onlineUsers[userIndex],
                            newMessagesCount: updatedNewMessagesCount,
                        };
                    }
                }
            });
        window.Echo.join('online-users')
            .here(users => {
                this.onlineUsers = users;
            })
            .joining(user => {
                this.onlineUsers.push(user);
            })
            .leaving(user => {
                this.onlineUsers = this.onlineUsers.filter(u => u.id !== user.id);
            });
    },
    methods: {
        openDialog(user) {
            if (user.id !== this.currentUser.id) {
                this.currentDialog = {
                    id: user.id,
                    name: user.name,
                    messages: [],
                };
                this.onlineUsers[this.onlineUsers.findIndex(u => u.id === user.id)] = {
                    ...user,
                    newMessagesCount: 0,
                };
                this.fetchChatHistory(this.currentUser.id, user.id);
            }
        },
        fetchChatHistory(senderId, receiverId) {
            const existingDialogIndex = this.currentDialog ? this.currentDialog.id : -1;
            if (existingDialogIndex === -1 || existingDialogIndex !== senderId) {
                axios.get(`/chat/messages/${receiverId}`)
                    .then(response => {
                        this.currentDialog = {
                            id: receiverId,
                            name: this.currentDialog.name,
                            messages: response.data,
                        };
                        this.onlineUsers[this.onlineUsers.findIndex(u => u.id === receiverId)] = {
                            ...this.onlineUsers.find(u => u.id === receiverId),
                            newMessagesCount: 0,
                        };
                    })
                    .catch(error => {
                        console.error('Error fetching chat history', error);
                    });
            }
        },
        sendMessage() {
            axios.post('/chat/send', {
                receiver_id: this.currentDialog.id,
                message: this.newMessage,
            })
                .then(response => {
                    this.currentDialog.messages.push({
                        message: this.newMessage,
                        name: this.currentUser.name
                    });
                    this.newMessage = '';
                })
                .catch(error => {
                    console.error('Error sending message', error);
                });
        },
    },
};
</script>
