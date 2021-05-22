/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/helium.js":
/*!********************************!*\
  !*** ./resources/js/helium.js ***!
  \********************************/
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

__webpack_require__(/*! ./tabs */ "./resources/js/tabs.js");

__webpack_require__(/*! ./toggle */ "./resources/js/toggle.js");

__webpack_require__(/*! ./repeater */ "./resources/js/repeater.js");

window.dispatchEvent(new Event('helium-init-forms'));

/***/ }),

/***/ "./resources/js/repeater.js":
/*!**********************************!*\
  !*** ./resources/js/repeater.js ***!
  \**********************************/
/***/ (() => {

var init = function init() {
  var repeaters = document.querySelectorAll('.helium-repeater-field');

  if (repeaters.length) {
    repeaters.forEach(function (repeater) {
      // Store the currently dragged form element
      var draggedForm = null; // Hook up the add buttons

      repeater.querySelectorAll('.helium-repeater-actions [data-action=add]:not([data-helium-init])').forEach(function (addButton) {
        addButton.addEventListener('click', function (event) {
          event.preventDefault();
          addNewForm(addButton.closest('.helium-repeater-field'));
        });
        addButton.setAttribute('data-helium-init', true);
      }); // Hook up the drag button

      repeater.querySelectorAll('.helium-repeater-form:not([data-helium-init])').forEach(function (form) {
        var field = repeater;
        var handle = form.querySelector('.helium-repeater-drag');
        handle.addEventListener('mousedown', function (event) {
          form.firstElementChild.setAttribute('draggable', true);
        });
        form.firstElementChild.addEventListener('dragstart', function (event) {
          draggedForm = form;
          form.style.zIndex = 1;
          form.firstElementChild.style.border = '1px dashed black';
          form.firstElementChild.style.backgroundColor = '#EFF6FF';
          form.firstElementChild.addEventListener('dragend', function (event) {
            form.style.zIndex = null;
            form.firstElementChild.style.border = null;
            form.firstElementChild.style.backgroundColor = null;
            form.firstElementChild.setAttribute('draggable', false);
            field.querySelectorAll('.helium-repeater-form-drop-above, .helium-repeater-form-drop-below').forEach(function (el) {
              // Disable all of the drop zones
              el.style.pointerEvents = null;
            });
          }, {
            once: true
          }); // Activate all of the drop zones

          field.querySelectorAll('.helium-repeater-form-drop-above, .helium-repeater-form-drop-below').forEach(function (el) {
            el.style.pointerEvents = 'auto';
          });
        });
        var dropAbove = form.querySelector('.helium-repeater-form-drop-above');
        dropAbove.addEventListener('dragenter', function (event) {
          if (draggedForm != form && nextFormSibling(draggedForm) != form) {
            if (form.offsetTop > draggedForm.offsetTop) {
              moveDownToBelow(draggedForm, previousFormSibling(form));
            } else {
              moveUpToAbove(draggedForm, form);
            }
          }

          ;
        });
        var dropBelow = form.querySelector('.helium-repeater-form-drop-below');
        dropBelow.addEventListener('dragenter', function (event) {
          if (draggedForm != form && previousFormSibling(draggedForm) != form) {
            if (form.offsetTop > draggedForm.offsetTop) {
              moveDownToBelow(draggedForm, form);
            } else {
              moveUpToAbove(draggedForm, nextFormSibling(form));
            }
          }

          ;
        });
        form.setAttribute('data-helium-init', true);
      }); // Hook up the remove buttons

      repeater.querySelectorAll('.helium-repeater-remove:not([data-helium-init])').forEach(function (removeButton) {
        removeButton.addEventListener('click', function (event) {
          event.preventDefault();
          var form = removeButton.closest('.helium-repeater-form');
          animateDestroy(form);
          reindex(repeater);
        });
        removeButton.setAttribute('data-helium-init', true);
      }); // Hook up the move up buttons

      repeater.querySelectorAll('.helium-repeater-move-up:not([data-helium-init])').forEach(function (upButton) {
        upButton.addEventListener('click', function () {
          var form = upButton.closest('.helium-repeater-form');
          var previous = previousFormSibling(form);

          if (previous) {
            moveUpToAbove(form, previous);
          }
        });
        upButton.setAttribute('data-helium-init', true);
      }); // Hook up the move down buttons

      repeater.querySelectorAll('.helium-repeater-move-down:not([data-helium-init])').forEach(function (downButton) {
        downButton.addEventListener('click', function () {
          var form = downButton.closest('.helium-repeater-form');
          var next = nextFormSibling(form);

          if (next) {
            moveDownToBelow(form, next);
          }
        });
        downButton.setAttribute('data-helium-init', true);
      });
      reindex(repeater);
    });
  }
};

function addNewForm(repeater) {
  fetch('/admin/entities/form-section', {
    method: 'POST',
    credentials: 'same-origin',
    headers: {
      'Content-Type': 'application/json'
    },
    body: repeater.getAttribute('data-add-request')
  }).then(function (response) {
    return response.text();
  }).then(function (data) {
    // Create a temp div to contain the new html
    var div = document.createElement('div');
    div.insertAdjacentHTML('beforeend', data); // Extract the form

    var form = div.querySelector('.helium-repeater-form');
    repeater.querySelector('.helium-repeater-forms-container').insertAdjacentElement('beforeend', form);
    animateAppear(form);
    reindex(repeater);
    window.dispatchEvent(new Event('helium-init-forms'));
  })["catch"](function (error) {
    console.log(error);
  });
}

function reindex(repeater) {
  var orderable = repeater.classList.contains('orderable');
  repeater.querySelectorAll('.helium-repeater-form').forEach(function (form, index) {
    if (orderable) {
      form.querySelector('.helium-sequence-field').value = index++;
    }

    form.querySelector('.helium-repeater-move-down').classList.toggle('hidden', !orderable || !nextFormSibling(form));
    form.querySelector('.helium-repeater-move-up').classList.toggle('hidden', !orderable || !previousFormSibling(form));
  });
}

function nextFormSibling(form) {
  var next = form.nextElementSibling;

  while (next && !next.matches('.helium-repeater-form:not(.removed)')) {
    next = next.nextElementSibling;
  }

  return next;
}

function previousFormSibling(form) {
  var previous = form.previousElementSibling;

  while (previous && !previous.matches('.helium-repeater-form:not(.removed)')) {
    previous = previous.previousElementSibling;
  }

  return previous;
}

function swapDown(item, swapWith) {
  swapWith.after(item);
  reindex(item.closest('.helium-repeater-field'));
  var top = swapWith.getBoundingClientRect().height;
  animateFrom(swapWith, top);
  top = -item.getBoundingClientRect().height;
  animateFrom(item, top);
}

function swapUp(item, swapWith) {
  swapWith.before(item);
  reindex(item.closest('.helium-repeater-field'));
  var top = item.getBoundingClientRect().height;
  animateFrom(item, top);
  top = -swapWith.getBoundingClientRect().height;
  animateFrom(swapWith, top);
}

function moveUpToAbove(item, above) {
  var totalOffset = 0;
  var top = item.getBoundingClientRect().height;
  var previous = previousFormSibling(item);
  var stopAt = previousFormSibling(above);

  while (previous && previous != stopAt) {
    totalOffset += previous.getBoundingClientRect().height;
    animateFrom(previous, -top);
    previous = previousFormSibling(previous);
  }

  above.before(item);
  animateFrom(item, totalOffset);
}

function moveDownToBelow(item, below) {
  var totalOffset = 0;
  var top = item.getBoundingClientRect().height;
  var next = nextFormSibling(item);
  var stopAt = nextFormSibling(below);

  while (next && next != stopAt) {
    totalOffset += next.getBoundingClientRect().height;
    animateFrom(next, top);
    next = nextFormSibling(next);
  }

  below.after(item);
  animateFrom(item, -totalOffset);
}

function animateFrom(item, top) {
  item.style.setProperty('--animStart', top + 'px');
  item.style.animation = 'move-to 0.3s';
  item.addEventListener('animationend', function () {
    item.style.animation = null;
  }, {
    once: true
  });
}

function animateDestroy(item) {
  item.classList.add('removed');
  item.style.setProperty('--animStart', item.getBoundingClientRect().height + 'px');
  item.style.animation = 'shrink-out 0.3s';
  item.addEventListener('animationend', function () {
    item.remove();
  }, {
    once: true
  });
}

function animateAppear(item) {
  item.style.setProperty('--animEnd', item.getBoundingClientRect().height + 'px');
  item.style.animation = 'grow-in 0.3s';
  item.addEventListener('animationend', function () {
    item.style.animation = null;
  }, {
    once: true
  });
}

window.addEventListener('helium-init-forms', init);

/***/ }),

/***/ "./resources/js/tabs.js":
/*!******************************!*\
  !*** ./resources/js/tabs.js ***!
  \******************************/
/***/ (() => {

var init = function init() {
  var tabs = document.querySelectorAll('.helium-tab');

  if (tabs.length) {
    tabs.forEach(function (tab) {
      tab.addEventListener('click', function (event) {
        setActiveTab(tab);
      });
    });
    setActiveTab(document.getElementById('helium-tab-main'));
  }
};

function setActiveTab(tab) {
  // Show the active tab content
  var tabContent = document.querySelectorAll('.helium-tab-content');
  tabContent.forEach(function (content) {
    content.style.display = tab.getAttribute('data-tab-content-id') == content.id ? 'block' : 'none';
  }); // Set the active tab

  var tabs = document.querySelectorAll('.helium-tab.active');
  tabs.forEach(function (tab) {
    tab.classList.remove('active');
  });
  tab.classList.add('active');
}

init();

/***/ }),

/***/ "./resources/js/toggle.js":
/*!********************************!*\
  !*** ./resources/js/toggle.js ***!
  \********************************/
/***/ (() => {

var init = function init() {
  var btns = document.getElementsByClassName('helium-toggle');
  Array.prototype.forEach.call(btns, function (btn) {
    var target = document.querySelector(btn.getAttribute('data-target'));

    if (!target.classList.contains('collapsed')) {
      target.style.maxHeight = target.scrollHeight + 'px';
    }

    btn.addEventListener('click', function (event) {
      if (!target.classList.contains('collapsed')) {
        target.style.maxHeight = '0px';
        target.classList.add('collapsed');
      } else {
        target.style.maxHeight = target.scrollHeight + 'px';
        target.classList.remove('collapsed');
      }
    });
  });
};

init();

/***/ }),

/***/ "./resources/css/helium.css":
/*!**********************************!*\
  !*** ./resources/css/helium.css ***!
  \**********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					result = fn();
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/public/js/helium": 0,
/******/ 			"public/css/helium": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			for(moduleId in moreModules) {
/******/ 				if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 					__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 				}
/******/ 			}
/******/ 			if(runtime) runtime(__webpack_require__);
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkIds[i]] = 0;
/******/ 			}
/******/ 			__webpack_require__.O();
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunk"] = self["webpackChunk"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["public/css/helium"], () => (__webpack_require__("./resources/js/helium.js")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["public/css/helium"], () => (__webpack_require__("./resources/css/helium.css")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;