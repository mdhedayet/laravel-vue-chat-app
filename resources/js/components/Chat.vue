<template>
 <div class="row">
    <div class="col-4">
        <div class="card card-default">
            <div class="card-header">Your Client</div>
            <div class="card-body p-0">
                <ul class="list-unstyled">
                    <li class="p-2" v-for="(eee,index) in friends" :key="index">
                        <a :href="'/chat/'+eee.user.id"><strong>{{eee.user.name}}</strong></a>

                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-8" v-if="friend">


        <div class="card" id="chat2">
          <div class="card-header d-flex justify-content-between align-items-center p-3">
            <h5 class="mb-0">{{friend.name}}</h5>
          </div>
          <div class="card-body" style="height:300px; overflow-y:scroll" ref="conversations">
            <div  v-for="(message, index) in messages" :key="index">
                    <div class="d-flex flex-row justify-content-start mb-4" v-if="message.user_id == friend.id">
                        <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3-bg.webp"
                            alt="avatar 1" style="width: 45px; height: 100%;">
                        <div>
                            <p class="small p-2 ms-3 mb-1 rounded-3" style="background-color: #f5f6f7;">{{message.message}}</p>
                            <p class="small ms-3 mb-3 rounded-3 text-muted">{{message.created_at}}</p>
                        </div>
                    </div>

                    <div class="d-flex flex-row justify-content-end" v-else>
                        <div>
                            <p class="small p-2 me-3 mb-1 text-white rounded-3 bg-primary">{{message.message}}</p>
                            <p class="small me-3 mb-3 rounded-3 text-muted d-flex justify-content-end">{{message.created_at}}</p>
                        </div>
                        <img :src="'/images/user/'+friend.image"
                            alt="avatar 1" style="width: 45px; height: 100%;">
                    </div>

            </div>

          </div>
          <div class="card-footer text-muted d-flex justify-content-start align-items-center p-3">
            <img :src="'/images/user/'+friend.image" alt="avatar 3" style="width: 40px; height: 100%;">
            <input
            @keyup.enter="sendMessageFnc(friend.id)"
            v-model="sentMessage"
            type="text" class="form-control form-control-lg mx-1"
              placeholder="Type message">
            <!-- <a class="ms-1 text-muted" href="#!"><i class="fas fa-paperclip"></i></a>
            <a class="ms-3 text-muted" href="#!"><i class="fas fa-smile"></i></a> -->
            <!-- <a class="ms-3" :href="sendMessageFnc(friend.id)"><i class="fas fa-paper-plane"></i></a> -->
          </div>
        </div>
    </div>
 </div>
</template>

<script>
export default {
    props: ["friends","chats","friend","me"],
    data(){
            return{
                messages:[],
                sentMessage:'',
            }
        },
    created(){
        this.oldMessage();
        Echo.channel('friend.'+this.me.id)
            .listen('SentMessage', (e) => {
                if(e.chat.chat_id == this.me.friends[0].chat_id){
                    this.messages.push(e.chat);
                }
                this.scrollToBottom();
            });
    },
    methods:{
        sendMessageFnc(id){
                if(this.sentMessage != ''){
                    this.messages.push({
                    message:this.sentMessage,
                    user_id:this.friend.user_id,
                    friend_id:this.friend.friend_id,
                    created_at:new Date().toLocaleString()
                });
                this.scrollToBottom();
                axios.post('/chat/'+id,{message:this.sentMessage}).then(response=>{
                    //console.log(response.data);
                    this.sentMessage='';
                }).catch(error=>{
                    console.log(error);
                })
            }
        },
        oldMessage(){
            this.messages= this.chats;
        },

        scrollToBottom() {
            setTimeout(() => {
                const container = this.$refs.conversations;
                container.scrollTop = container.scrollHeight;
            }, 100);
        }

    },
    mounted(){
        this.scrollToBottom();
    },
}
</script>

<style scoped>
#chat2 .form-control {
border-color: #dfdfdf;
}

#chat2 .form-control:focus {
border-color: transparent;
box-shadow: inset 0px 0px 0px 1px transparent;
}

.divider:after,
.divider:before {
content: "";
flex: 1;
height: 1px;
background: #eee;
}
</style>
