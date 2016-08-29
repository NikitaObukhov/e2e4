'use strict';
angular.module('core')
  .provider('Config', function () {
    var data = {};
    return {
      $get: function() {
        return this;
      },
      set: function(name, value) {
        data[name] = value;
      },
      get: function(name) {
        return data[name];
      }
    }
  });
