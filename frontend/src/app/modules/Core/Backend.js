'use strict';
angular.module('core')
  .config(function(RestangularProvider) {
    RestangularProvider.setDefaultHeaders({
      'Content-Type': 'application/json'
    });

    RestangularProvider.addResponseInterceptor(function(data, operation, what, url, response, deferred) {
      if( _.isObject(data) === false ) {
        if( data[0] === '<') {
          deferred.reject(data);
          console.log('restangular interceptor: not json:', data);
        }
      }
      return data;
    });
  })
  .factory('Backend', function (Restangular, Config, $rootScope) {
    function Collection(array, total) {
      this.prototype = array;
      this.total = total;
    };
    Collection.prototype = new Array;
    return Restangular.withConfig(function (RestangularConfigurer) {

      RestangularConfigurer
        .setBaseUrl(Config.get('Backend.baseUrl') + Config.get('Backend.basePath'))
        .setRestangularFields({
          selfLink: 'self.link'
        })
        .addResponseInterceptor(function(data, operation, what, url, response, deferred) {
          if ('getList' === operation) {
            data.prototype = new Collection(data, parseInt(response.headers('x-pagination-total-count')));
          }
          return data;
        })
        .setErrorInterceptor(function(response, deferred, responseHandler) {
          console.debug(arguments);
          window.args = arguments;
          return true;
        })
      ;
    });
  });
