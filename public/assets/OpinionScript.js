const formOpinion = document.querySelector('form');
const opinions = document.querySelector('#opinions');

formOpinion.addEventListener('submit', function (e) {
  e.preventDefault();

  fetch(this.action, {
    body: new FormData(e.target),
    method: 'POST'
  })
    .then(response => response.json())
    .then(json => {
      handleResponse(json);
    });
})

const handleResponse = function (response) {
  opinions.innerHTML += response.html;
}