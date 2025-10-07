document.addEventListener('DOMContentLoaded', function () {
  // Hide all reply boxes on load
  document.querySelectorAll('.replyBox, .nested_replyBox').forEach(function (box) {
    box.style.display = 'none';
  });

  // Main post reply
  const mainReplyBtn = document.querySelector('.showReplyBtn');
  const mainReplyBox = document.querySelector('#showPost .replyBox');
  const mainCancelBtn = mainReplyBox?.querySelector('.cancelBtn');

  if (mainReplyBtn && mainReplyBox && mainCancelBtn) {
    mainReplyBtn.addEventListener('click', function () {
      mainReplyBox.style.display = 'block';
      mainReplyBtn.style.display = 'none';
    });

    mainCancelBtn.addEventListener('click', function () {
      mainReplyBox.style.display = 'none';
      mainReplyBtn.style.display = 'inline-block';
    });
  }

  // Nested replies
  document.querySelectorAll('.nested_showReplyBtn').forEach(function (btn) {
    const replyContainer = btn.closest('.reply');
    const replyBox = replyContainer.querySelector('.nested_replyBox');
    const cancelBtn = replyBox.querySelector('.nested_cancelBtn');

    btn.addEventListener('click', function () {
      replyBox.style.display = 'block';
      btn.style.display = 'none';
    });

    cancelBtn.addEventListener('click', function () {
      replyBox.style.display = 'none';
      btn.style.display = 'inline-block';
    });
  });
});
