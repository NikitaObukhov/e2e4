angular
  .module('app')
  .component('searchResults', {
    templateUrl: 'app/components/SearchResults.html',
    controller: searchResults
  });

function searchResults($stateParams, NgTableParams, SearchResultRepository, SearchRequestRepository) {
  var that = this;
  this.nameOfDataColumn = 'Foo';
  SearchRequestRepository.get($stateParams.id).then(function(searchRequest) {
    that.nameOfDataColumn = searchRequest.getNameOfDataColumn();
    that.searchRequest = searchRequest;
  });
  window.c = that;
  this.table = new NgTableParams({}, {
    getData: function(params) {
      var promise = SearchResultRepository.getList($stateParams.id, {
        page: params.page(),
        'per-page': params.count()
      });
      promise.then(function(collection) {
        window.collection = collection;
        params.total(collection.prototype.total);
      });
      return promise;
    }
  });
}