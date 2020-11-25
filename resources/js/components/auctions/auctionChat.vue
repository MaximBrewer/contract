<template>
  <div>
    <beautiful-chat
      :participants="participants"
      :titleImageUrl="titleImageUrl"
      :onMessageWasSent="onMessageWasSent"
      :messageList="auctionMessages"
      :newMessagesCount="newMessagesCount"
      :isOpen="isChatOpen"
      :close="closeChat"
      :icons="icons"
      :open="openChat"
      :showEmoji="false"
      :placeholder="placeholderMessage"
      :showFile="false"
      :showTypingIndicator="showTypingIndicator"
      :colors="colors"
      :alwaysScrollToBottom="alwaysScrollToBottom"
      :messageStyling="messageStyling"
      @onType="handleOnType"
      @edit="editMessage"
      @remove="deleteMessage"
    >
      <template v-slot:header>&nbsp;</template>
      <template v-slot:text-message-body="{ message, me }">
        <div style="font-weight:bold;">{{message.data.text}}</div>
        <i>
          <small>{{me ? user.contragents[0].title : message.author }} - {{ message.created_at | formatChatTime}}</small>
        </i>
      </template>
      <template v-slot:user-avatar>&nbsp;</template>

      <!-- <template v-slot:user-avatar="scopedProps">
        <slot name="user-avatar" :user="scopedProps.user" :message="scopedProps.message">
        </slot>
      </template>
      <template v-slot:text-message-body="scopedProps">
        <slot name="text-message-body" :message="scopedProps.message" :messageText="scopedProps.messageText" :messageColors="scopedProps.messageColors" :me="scopedProps.me">
        </slot>
      </template>-->
    </beautiful-chat>
  </div>
</template>
<script>
import CloseIcon from "../../icons/close-icon.png";
import OpenIcon from "../../icons/logo-no-bg.svg";
import FileIcon from "../../icons/file.svg";
import CloseIconSvg from "../../icons/close.svg";
export default {
  props: ["auction"],
  computed: {
    auctionMessages: function() {
      let ar = [];
      for (let j in this.auction.messages)
        ar.push({
          id: this.auction.messages[j].id,
          author:
            this.user.id == this.auction.messages[j].user_id
              ? "me"
              : this.auction.messages[j].author,
          auction_id: this.auction.messages[j].auction_id,
          type: this.auction.messages[j].type,
          data: this.auction.messages[j].data,
          created_at: this.auction.messages[j].created_at,
          updated_at: this.auction.messages[j].updated_at
        });
      return ar;
    }
  },
  data: function() {
    return {
      placeholderMessage: this.__("Write a message..."),
      icons: {
        open: {
          img: OpenIcon,
          name: "default"
        },
        close: {
          img: CloseIcon,
          name: "default"
        },
        file: {
          img: FileIcon,
          name: "default"
        },
        closeSvg: {
          img: CloseIconSvg,
          name: "default"
        }
      },
      participants: [], // the list of all the participant of the conversation. `name` is the user name, `id` is used to establish the author of a message, `imageUrl` is supposed to be the user avatar.
      titleImageUrl: "",
      messageList: [], // the list of the messages to show, can be paginated and adjusted dynamically
      newMessagesCount: 0,
      isChatOpen: true, // to determine whether the chat window should be open or closed
      showTypingIndicator: "", // when set to a value matching the participant.id it shows the typing indicator for the specific user
      colors: {
        header: {
          bg: "#343a40",
          text: "#ffffff"
        },
        launcher: {
          bg: "#343a40"
        },
        messageList: {
          bg: "#ffffff"
        },
        sentMessage: {
          bg: "#343a40",
          text: "#ffffff"
        },
        receivedMessage: {
          bg: "#eaeaea",
          text: "#222222"
        },
        userInput: {
          bg: "#f4f7f9",
          text: "#565867"
        }
      }, // specifies the color scheme for the component
      alwaysScrollToBottom: true, // when set to true always scrolls the chat to the bottom when new events are in (new message, user starts typing...)
      messageStyling: true // enables *bold* /emph/ _underline_ and such (more info at github.com/mattezza/msgdown)
    };
  },
  methods: {
    onMessageWasSent(message) {
      this.sendMessage(message);
      // called when the user sends a message
      //this.messageList = [...this.messageList, message];
    },
    editMessage(message) {
      // called when the user sends a message
      //this.messageList = [...this.messageList, message];
      return message;
    },
    deleteMessage(message) {
      axios.delete(
        "/web/v1/messages/" + message.id
      );
    },
    sendMessage(body) {
      let app = this;
      let message = {
        auction_id: app.auction.id,
        user_id: app.user.id,
        message: body.data.text
      };
      axios
        .post("/web/v1/messages", message)
        .then(function(resp) {})
        .catch(function(errors) {
          app.$fire({
            title: app.__("Error!"),
            text: errors.response.data.message,
            type: "error",
            timer: 5000
          });
        });
    },
    openChat() {
      // called when the user clicks on the fab button to open the chat
      this.isChatOpen = true;
      this.newMessagesCount = 0;
    },
    closeChat() {
      // called when the user clicks on the botton to close the chat
      this.isChatOpen = false;
    },
    handleScrollToTop() {
      // called when the user scrolls message list to top
      // leverage pagination for loading another page of messages
    },
    handleOnType() {},
    editMessage(message) {
      const m = this.messageList.find(m => m.id === message.id);
      m.isEdited = true;
      m.data.text = message.data.text;
    }
  }
};
</script>
</script>