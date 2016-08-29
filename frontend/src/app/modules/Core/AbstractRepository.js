'use strict';
angular.module('core')
/**
 * Default REST usage of http verbs
 */
  .factory('AbstractRepository', function ($injector) {
    function BaseModel() {

    };
    function Collection() {

    };
    var AbstractRepository = function(restangular, route, model, fabrique) {
      this.route = route;
      this.model = model || BaseModel;
      this.restangular = restangular.withConfig(function(RestangularConfigurer) {
        RestangularConfigurer.setOnElemRestangularized(fabrique);
      });
    };

    AbstractRepository.prototype = {
      getList: function (params) {
        // TODO maybe there is a way to fix cross-origin issue without adding slash
        return this.restangular.all(this.route + '/').getList(params);
      },
      get: function (id) {
        return this.restangular.one(this.route, id).get();
      },
      create: function (newResource) {
        if (angular.isUndefined(newResource.originalElement) === false) {
          delete newResource.originalElement;
        }
        // TODO maybe there is a way to fix cross-origin issue without adding slash
        return this.restangular.all(this.route).post(newResource);
      }
    };


    return AbstractRepository;
  });
