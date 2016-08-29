angular
  .module('app')
  .component('searchResultRow', {
    transclude: true,
    templateUrl: 'app/components/SearchResultRow.html',
    controller: SearchResultRow
  });

function SearchResultRow() {

}
