<template>
  <div class="comments-app">
    <h1>{{ __('Comments') }}</h1>
    <!-- From -->
    <div class="comment-form" v-if="user">
      <!-- Comment Avatar -->
      <div class="comment-avatar">
        <img src="/storage/commentbox.png" />
      </div>
      <form class="form" name="form">
        <div class="form-row">
          <textarea class="input" :placeholder="__('Add comment...')" required v-model="message"></textarea>
          <span class="input" v-if="errorComment" style="color:red">{{errorComment}}</span>
        </div>
        <div class="form-row">
          <input class="input" placeholder="Email" type="text" disabled :value="user.email" />
        </div>
        <div class="form-row">
          <input
            type="button"
            class="btn btn-success"
            @click="saveComment"
            :value="__('Add Comment')"
          />
        </div>
      </form>
    </div>
    <!-- Comments List -->
    <div class="comments" v-if="comments" v-for="(comment,index) in commentsData">
      <!-- Comment -->
      <div v-if="!spamComments[index] || !comment.spam" class="comment">
        <!-- Comment Avatar -->
        <div class="comment-avatar">
          <img src="/storage/comment.png" />
        </div>
        <!-- Comment Box -->
        <div class="comment-box">
          <div class="comment-text">{{comment.comment}}</div>
          <div class="comment-footer">
            <div class="comment-info">
              <span class="comment-author">
                <em>{{ comment.name}}</em>
              </span>
              <span class="comment-date">{{ comment.date}}</span>
            </div>
            <div class="comment-actions">
              <ul class="list">
                <li>
                  {{ __('Votes') }}: {{comment.votes}}
                  <a
                    v-if="!comment.votedByUser"
                    v-on:click="voteComment(comment.commentid,'directcomment',index,0,'up')"
                  >{{ __('Up Votes') }}</a>
                  <a
                    v-if="!comment.votedByUser"
                    v-on:click="voteComment(comment.commentid,'directcomment',index,0,'down')"
                  >{{ __('Down Votes') }}</a>
                </li>
                <li>
                  <a
                    v-on:click="spamComment(comment.commentId,'directcomment',index,0)"
                  >{{ __('Spam') }}</a>
                </li>
                <li>
                  <a v-on:click="openComment(index)">{{ __('Reply') }}</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <!-- From -->
        <div class="comment-form comment-v" v-if="commentBoxs[index]">
          <!-- Comment Avatar -->
          <div class="comment-avatar">
            <img src="/storage/comment.png" />
          </div>
          <form class="form" name="form">
            <div class="form-row">
              <textarea
                class="input"
                :placeholder="__('Add Comment...')"
                required
                v-model="message"
              ></textarea>
              <span class="input" v-if="errorReply" style="color:red">{{errorReply}}</span>
            </div>
            <div class="form-row">
              <input class="input" placeholder="Email" type="text" :value="user.name" />
            </div>
            <div class="form-row">
              <input
                type="button"
                class="btn btn-success"
                v-on:click="replyComment(comment.commentid,index)"
                :value="__('Add Comment')"
              />
            </div>
          </form>
        </div>
        <!-- Comment - Reply -->
        <div v-if="comment.replies">
          <div class="comments" v-for="(replies,index2) in comment.replies">
            <div v-if="!spamCommentsReply[index2] || !replies.spam" class="comment reply">
              <!-- Comment Avatar -->
              <div class="comment-avatar">
                <img src="/storage/comment.png" />
              </div>
              <!-- Comment Box -->
              <div class="comment-box" style="background: grey;">
                <div class="comment-text" style="color: white">{{replies.comment}}</div>
                <div class="comment-footer">
                  <div class="comment-info">
                    <span class="comment-author">{{replies.name}}</span>
                    <span class="comment-date">{{replies.date}}</span>
                  </div>
                  <div class="comment-actions">
                    <ul class="list">
                      <li>
                        {{ __('Total votes') }}: {{replies.votes}}
                        <a
                          v-if="!replies.votedByUser"
                          v-on:click="voteComment(replies.commentid,'replycomment',index,index2,'up')"
                        >{{ __('Up Votes') }}</a>
                        <a
                          v-if="!replies.votedByUser"
                          v-on:click="voteComment(comment.commentid,'replycomment',index,index2,'down')"
                        >{{ __('Down Votes') }}</a>
                      </li>
                      <li>
                        <a
                          v-on:click="spamComment(replies.commentid,'replycomment',index,index2)"
                        >{{ __('Spam') }}</a>
                      </li>
                      <li>
                        <a v-on:click="replyCommentBox(index2)">{{ __('Reply') }}</a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <!-- From -->
              <div class="comment-form reply" v-if="replyCommentBoxs[index2]">
                <!-- Comment Avatar -->
                <div class="comment-avatar">
                  <img src="/storage/comment.png" />
                </div>
                <form class="form" name="form">
                  <div class="form-row">
                    <textarea
                      class="input"
                      :placeholder="__('Add comment...')"
                      required
                      v-model="message"
                    ></textarea>
                    <span class="input" v-if="errorReply" style="color:red">{{errorReply}}</span>
                  </div>
                  <div class="form-row">
                    <input class="input" placeholder="Email" type="text" :value="user.name" />
                  </div>
                  <div class="form-row">
                    <input
                      type="button"
                      class="btn btn-success"
                      v-on:click="replyComment(comment.commentid,index)"
                      :value="__('Add Comment')"
                    />
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
var _ = require("lodash");
export default {
  data() {
    return {
      comments: [],
      commentreplies: [],
      comments: 0,
      commentBoxs: [],
      message: null,
      replyCommentBoxs: [],
      commentsData: [],
      viewcomment: [],
      show: [],
      spamCommentsReply: [],
      spamComments: [],
      errorComment: null,
      errorReply: null,
      user: window.user,
      commentUrl: null
    };
  },
  methods: {
    fetchComments() {
      let app = this;
      app.commentUrl = app.$route.params.id;
      axios
        .get(
          "/api/v1/comments/" +
            app.commentUrl +
            "?csrf_token=" +
            window.csrf_token +
            "&api_token=" +
            window.api_token
        )
        .then(function(resp) {
          app.commentsData = resp.data;
          app.commentsData = _.orderBy(resp.data, ["votes"], ["desc"]);
          app.comments = 1;
          app.isLoading = false;
        })
        .catch(function() {
          alert(app.__("Failed to load comments"));
          app.isLoading = false;
        });
    },

    showComments(index) {
      let app = this;
      if (!app.viewcomment[index]) {
        Vue.set(app.show, index, "hide");
        Vue.set(app.viewcomment, index, 1);
      } else {
        Vue.set(app.show, index, "view");
        Vue.set(app.viewcomment, index, 0);
      }
    },
    openComment(index) {
      let app = this;
      if (app.user) {
        if (app.commentBoxs[index]) {
          Vue.set(app.commentBoxs, index, 0);
        } else {
          Vue.set(app.commentBoxs, index, 1);
        }
      }
    },
    replyCommentBox(index) {
      let app = this;
      if (app.user) {
        if (app.replyCommentBoxs[index]) {
          Vue.set(app.replyCommentBoxs, index, 0);
        } else {
          Vue.set(app.replyCommentBoxs, index, 1);
        }
      }
    },
    saveComment() {
      var app = this;
      if (app.message != null && app.message != " ") {
        app.errorComment = null;
        axios
          .post(
            "/api/v1/comments?csrf_token=" +
              window.csrf_token +
              "&api_token=" +
              window.api_token,
            {
              contragent_id: app.commentUrl,
              comment: app.message,
              user_id: app.user.id
            }
          )
          .then(function(resp) {
            if (resp.data.status) {
              app.commentsData.push({
                commentid: resp.data.commentId,
                name: app.user.name,
                comment: app.message,
                votes: 0,
                reply: 0,
                replies: []
              });
              app.message = null;
            }
          });
      } else {
        app.errorComment = "Please enter a comment to save";
      }
    },
    replyComment(commentId, index) {
      let app = this;
      if (!!!app.commentsData[index].replies)
        app.commentsData[index].replies = [];
      if (app.message != null && app.message != " ") {
        app.errorReply = null;
        axios
          .post(
            "/api/v1/comments?csrf_token=" +
              window.csrf_token +
              "&api_token=" +
              window.api_token,
            {
              comment: app.message,
              user_id: app.user.id,
              reply_id: commentId
            }
          )
          .then(function(res) {
            if (res.data.status) {
              if (!app.commentsData[index].reply) {
                app.commentsData[index].replies.push({
                  commentid: res.data.commentId,
                  name: app.user.name,
                  comment: app.message,
                  votes: 0
                });
                app.commentsData[index].reply = 1;
                Vue.set(app.replyCommentBoxs, index, 0);
                Vue.set(app.commentBoxs, index, 0);
              } else {
                app.commentsData[index].replies.push({
                  commentid: res.data.commentId,
                  name: app.user.name,
                  comment: app.message,
                  votes: 0
                });
                Vue.set(app.replyCommentBoxs, index, 0);
                Vue.set(app.commentBoxs, index, 0);
              }
              app.message = null;
            }
          });
      } else {
        app.errorReply = "Please enter a comment to save";
      }
    },
    voteComment(commentId, commentType, index, index2, voteType) {
      let app = this;
      if (app.user) {
        axios
          .post(
            "/api/v1/comments/" +
              commentId +
              "/vote?csrf_token=" +
              window.csrf_token +
              "&api_token=" +
              window.api_token,
            {
              user_id: app.user.id,
              vote: voteType
            }
          )
          .then(function(res) {
            if (res.data) {
              if (commentType == "directcomment") {
                if (voteType == "up") {
                  app.commentsData[index].votes++;
                } else if (voteType == "down") {
                  app.commentsData[index].votes--;
                }
              } else if (commentType == "replycomment") {
                if (voteType == "up") {
                  app.commentsData[index].replies[index2].votes++;
                } else if (voteType == "down") {
                  app.commentsData[index].replies[index2].votes--;
                }
              }
            }
          });
      }
    },
    spamComment(commentId, commentType, index, index2) {
      let app = this;
      console.log("spam here");
      if (app.user) {
        axios
          .post(
            "/api/v1/comments/" +
              commentId +
              "/spam?csrf_token=" +
              window.csrf_token +
              "&api_token=" +
              window.api_token,
            {
              user_id: app.user.id
            }
          )
          .then(function(res) {
            if (commentType == "directcomment") {
              Vue.set(app.spamComments, index, 1);
              Vue.set(app.viewcomment, index, 1);
            } else if (commentType == "replycomment") {
              Vue.set(app.spamCommentsReply, index2, 1);
            }
          });
      }
    }
  },
  mounted() {
    this.fetchComments();
  }
};
</script>