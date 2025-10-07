window.addEventListener('DOMContentLoaded', function () {
  const showFormBtn = document.getElementById('showFormBtn');
  const formDiv = document.getElementById('discussionForm');
  const cancelBtn = document.getElementById('cancelBtn');

  showFormBtn.addEventListener('click', () => {
    formDiv.classList.remove('hidden');
  });

  cancelBtn.addEventListener('click', () => {
    formDiv.classList.add('hidden');
  });
});
