(function($) {
  'use strict';
  // HubSpot Env
  var leadinConfig = window.leadin_config || {};
  var hubspotBaseUrl = leadinConfig.hubspotBaseUrl;
  var portalId = leadinConfig.portalId;

  /**
   * DOM
   */
  var domElements = {
    iframe: document.getElementById('leadin-iframe'),
  };

  /**
   * Chatflows
   */
  function initChatFlows() {
    var leadinMenu = document.getElementById('toplevel_page_leadin');
    var firstSubMenu = leadinMenu && leadinMenu.querySelector('.wp-first-item');
    var chatflowsUrl = hubspotBaseUrl + '/chatflows/' + portalId;
    var chatflowsHtml =
      '<li><a href="' + chatflowsUrl + '" target="_blank">Chatflows</a></li>';
    if (firstSubMenu) {
      firstSubMenu.insertAdjacentHTML('afterend', chatflowsHtml);
    }
  }

  /**
   * Interframe
   */
  var Interframe = (function() {
    var eventBus = $({});

    function handleMessage(message) {
      eventBus.trigger('message', message);
    }

    function handleMessageEvent(event) {
      if (event.origin === hubspotBaseUrl) {
        try {
          const data = JSON.parse(event.data);
          handleMessage(data);
        } catch (e) {
          // Error in parsing message
        }
      }
    }

    function postMessage(message) {
      domElements.iframe.contentWindow.postMessage(
        JSON.stringify(message),
        hubspotBaseUrl
      );
    }

    return {
      init: function() {
        window.addEventListener('message', handleMessageEvent);
      },
      onMessage: function(callback) {
        eventBus.on('message', callback);
      },
      postMessage: postMessage,
      reply: function(message, payload) {
        var newMessage = Object.assign({}, message);
        newMessage.response = payload;
        postMessage(newMessage);
      },
    };
  })();

  /**
   * HubspotPluginUI API
   *
   * All outgoing messages are defined here
   */
  var HubspotPluginAPI = (function() {
    function setConfig() {
      Interframe.postMessage({ leadin_config: leadinConfig });
    }

    var api = {
      setConfig: setConfig,
    };

    return api;
  })();

  /**
   * Bridge API
   *
   * All incoming messages are handled here
   */
  var MessagesHandlers = (function() {
    var eventBus = $({});

    eventBus.on('leadin_parent_ajax', function(event, payload, reply) {
      var ajaxPayload = Object.assign(
        {
          complete: function(jqXHR, textStatus) {
            var response = Object.assign({ textStatus: textStatus }, jqXHR);
            reply(response);
          },
          error: function() {
            // TODO: sentry
          },
        },
        payload
      );
      $.ajax(ajaxPayload);
    });

    eventBus.on('leadin_page_reload', function() {
      window.location.reload();
    });

    eventBus.on('leadin_get_config', function() {
      HubspotPluginAPI.setConfig();
    });

    eventBus.on('leadin_clear_query_param', function() {
      var currentWindowLocation = window.location.toString();
      if (currentWindowLocation.indexOf('?') > 0) {
        currentWindowLocation = currentWindowLocation.substring(
          0,
          currentWindowLocation.indexOf('?')
        );
      }
      var newWindowLocation = currentWindowLocation + '?page=leadin';
      window.history.pushState({}, '', newWindowLocation);
    });

    return {
      start() {
        Interframe.onMessage(function(event, message) {
          function reply(payload) {
            Interframe.reply(message, payload);
          }

          for (var command in message) {
            eventBus.trigger(command, [message[command], reply]);
          }
        });
      },
    };
  })();

  /**
   * Main
   */
  function main() {
    MessagesHandlers.start();
    Interframe.init();
    initChatFlows();
  }

  main();
})(jQuery);
