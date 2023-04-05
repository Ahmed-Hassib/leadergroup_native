
var client_id_search = document.querySelector("#client-id");
var client_name_search = document.querySelector("#client-name");
var client_name_result = document.querySelector('#clients-names');
var delete_mal_btn = document.querySelectorAll('#delete-mal');
var confirm_delete_malfunction = document.querySelector("#confirm-delete-malfunction");

// self invoke function
(function () {
  // check if delete malfunction button is null 
  if (delete_mal_btn != null) {
    // loop on it
    delete_mal_btn.forEach(element => {
      element.addEventListener("click", (evt) => {
        confirm_delete_malfunction.setAttribute('href', `?do=delete-malfunction&mal-id=${element.dataset.malId}`);
      })
    });
  }
})();


/**
 * search_name function
 * this function is used to search about the client name
 */
function search_name(input) {
  // get name to search
  let client_name = input.value;
  let company_id = input.dataset.companyId;
  // check if client name box is empty or not
  if (client_name != '' && client_name.length != 0) {
    // send request
    $.get(`../requests/index.php?do=search&client-name=${client_name}&company-id=${company_id}`, (data) => {
      // convert the json data into string
      let src = $.parseJSON(data);
      // clear all previous clients names
      client_name_result.innerHTML = '';
      // check the result length
      if (src.length > 0) {
        // loop on data result to display the src
        for (let i = 0; i < src.length; i++) {
          // create a new li element
          let li = document.createElement('li');
          // add client id as an attribute
          li.setAttribute('data-id', src[i]['id']);
          // add client name as a text content
          li.textContent = src[i]['full_name'];
          // add event
          li.addEventListener('click', function (evt) {
            client_name_search.value = li.textContent;
            client_name_search.dataset.valid = true;
            client_id_search.value = li.getAttribute('data-id');
            // clear all previous clients names
            client_name_result.innerHTML = '';
            // show the result
            client_name_result.style.display = 'none';
          });
          // add an event when click on the one of the clients
          client_name_result.appendChild(li);
        }
      }
    });
    // show the result
    client_name_result.style.display = 'block';
  } else {
    // clear all previous clients names
    client_name_result.innerHTML = '';
    // hide the result
    client_name_result.style.display = 'none';
    // clear client id
    client_id_search.value = '';
  }
}

/**
 * 
 */
function show_media_preview(evt) {
  // get media container
  let media_container = document.querySelector('#media-container');
  // loop on files of the form
  for (let i = 0; i < evt.files.length; i++) {
    // uploaded type
    let type = evt.files[i]['type'].includes('video') ? 'video' : 'img';
    // create the image src
    var src = URL.createObjectURL(evt.files[i]);
    // create a colomn to append the image
    let col = document.createElement('div');
    col.classList.add('col-12', 'col-media');
    // element that will append to the preview box
    let element;
    // switch ... case ...
    switch (type) {
      case 'video':
        element = create_video_element(src);
        // append video source
        break;

      case 'img':
        // create image
        element = document.createElement('img');
        element.setAttribute('src', src);
        element.setAttribute('class', 'w-100 h-100');
        break;
    }
    // append image into column
    col.appendChild(element);
    // // create a control button
    // let delete_btn = create_control_buttons(element);
    // // append control buttons
    // col.appendChild(delete_btn);
    // append column into the preview box
    media_container.appendChild(col)
  }
}


function create_video_element(video_src) {
  // create video
  let video_element = document.createElement('video');
  video_element.classList.add('w-100', 'h-100');
  video_element.autoplay = 'autoplay';
  video_element.muted = true;
  video_element.controls = true;
  // video_element.loop = true;

  // create source tag
  let video_source_mp4 = document.createElement('source');
  video_source_mp4.src = video_src;
  video_source_mp4.type = `video/mp4`;
  video_element.appendChild(video_source_mp4);

  // create source tag
  let video_source_mov = document.createElement('source');
  video_source_mov.src = video_src;
  video_source_mov.type = `video/mov`;
  video_element.appendChild(video_source_mov);

  // create source tag
  let video_source_webm = document.createElement('source');
  video_source_webm.src = video_src;
  video_source_webm.type = `video/webm`;
  video_element.appendChild(video_source_webm);

  // return video element
  return video_element;
}

function add_new_media() {
  // get media container
  let media_container = document.querySelector('#media-container');
  // file inputs
  let file_inputs_container = document.querySelector('#file-inputs');
  // check media container
  if (media_container.childElementCount == 1) {
    // media container element
    let element = media_container.children[0];
    // remove all container element
    if (element.tagName.toLocaleLowerCase() == 'div') media_container.innerHTML = ''
  }
  // create file input
  let file_input = create_input_file();
  // append file input into media container
  file_inputs_container.append(file_input)
  // fire click
  file_input.click()
}

function create_input_file() {
  // create a media input
  let input = document.createElement('input');
  input.type = 'file';  // input type
  input.name = 'mal-media[]';   // input name
  input.setAttribute('multiple', 'multiple');
  input.setAttribute('form', 'edit-malfunction-info');
  // input.classList.add('d-none');
  // add event
  input.addEventListener('change', (evt) => {
    evt.preventDefault();
    // show media
    show_media_preview(input);
  })
  // return input
  return input;
}

function create_control_buttons(el_parent) {
  // create delete button
  let delete_button = document.createElement('button')
  delete_button.type = 'button';
  delete_button.classList.add('btn', 'btn-danger', 'py-1', 'ms-1')
  delete_button.innerHTML = "<i class='bi bi-trash'></i>";
  // create view button
  let view_button = document.createElement('button')
  view_button.type = 'button';
  view_button.classList.add('btn', 'btn-primary', 'py-1', 'me-1')
  view_button.innerHTML = "<i class='bi bi-eye'></i>";

  view_button.addEventListener('click', (evt) => {
    evt.preventDefault();
    openFullscreen(el_parent);
  })
  // create buttons container
  let btn_container = document.createElement('div');
  btn_container.classList.add('control-btn');
  // append buttons
  btn_container.appendChild(delete_button);
  btn_container.appendChild(view_button);
  // return button
  return btn_container;
}


function openFullscreen(el) {
  if (el.requestFullscreen) {
    el.requestFullscreen();
  } else if (el.webkitRequestFullscreen) { /* Safari */
    el.webkitRequestFullscreen();
  } else if (el.msRequestFullscreen) { /* IE11 */
    el.msRequestFullscreen();
  }
}

function closeFullscreen() {
  if (document.exitFullscreen) {
    document.exitFullscreen();
  } else if (document.webkitExitFullscreen) { /* Safari */
    document.webkitExitFullscreen();
  } else if (document.msExitFullscreen) { /* IE11 */
    document.msExitFullscreen();
  }
}