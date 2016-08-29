'use strict';
angular.module('search')
  .factory('SearchResultRepository', function (AbstractRepository, Backend, SearchRequestRepository, $injector) {
    function AbstractSearchResult() {

    };
    AbstractSearchResult.extend = function (ConcreteSearchResult) {
      ConcreteSearchResult.prototype = Object.create(AbstractSearchResult.prototype);
      ConcreteSearchResult.prototype.constructor = ConcreteSearchResult;
    };
    function Image() {
      AbstractSearchResult.call(this);
      this.getView = function() {
        return '<img src="'+ this.page_content.data +'"/>';
      };
    };
    function Link() {
      AbstractSearchResult.call(this);
      this.getView = function() {
        return '<a href="'+ this.page_content.data.uri +'">'+ (this.page_content.data.text || this.page_content.data.uri) +'</a>';
      };
    };
    function Text() {
      AbstractSearchResult.call(this);
      this.getView = function() {
        return this.page_content.data;
      };
    };
    var map = {
      image: Image,
      link: Link,
      text: Text
    };
    AbstractSearchResult.discriminator = function(element) {
      return map[element.page_content.type];
    };
    var repository =  new AbstractRepository(Backend, undefined, AbstractSearchResult, function(element, isCollection, what, backend) {
      if (isCollection) {
        return element;
      }
      var instance = $injector.instantiate(AbstractSearchResult.discriminator(element));
      _.extend(instance, element);
      return instance;
    });
    var defaultParams = {};
    repository.get = function (id, params) {
      throw 'No endpoint';
    };
    repository.getList = function (id, params) {
      return this.restangular.all(SearchRequestRepository.route + '/' + id + '/results').getList(_.merge({expand: 'pageContent'}, params));
    };
    repository.create = function(searchRequest) {
      throw 'No endpoint';
    };
    return repository;
  });

