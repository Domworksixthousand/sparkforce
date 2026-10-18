//real time messages
document.addEventListener("DOMContentLoaded", function(){
  const chatUserId = window.chatUserId;

  async function pollMessages() {
    try {
      let res = await fetch(`fetch_chat.php?id=${chatUserId}`);
      let html = await res.text();

      const messageBody = document.getElementById('message_body');
      messageBody.innerHTML = html;

      // Auto scroll to bottom on new message
      messageBody.scrollTop = messageBody.scrollHeight;
    } catch (e) {
      console.error('Polling Error:', e);
    }
  }

  window.pollMessages = pollMessages;

  pollMessages();
  setInterval(pollMessages, 3000);
});
