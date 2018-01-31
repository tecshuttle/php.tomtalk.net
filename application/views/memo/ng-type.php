<div ng-view></div>

<script id="templates/list.html" type="text/ng-template">
    <div ng-repeat="type in types">
        <a href="#edit/{{type.id}}">
            <div style="width:20%;float:left;cursor:pointer;">
                <div ng-bind="type.sync_status"></div>
                <div style="color:#{{type.color}};" ng-bind="type.name"></div>
                <div ng-bind="type.priority"></div>
            </div>
        </a>
    </div>
</script>

<script id="templates/edit.html" type="text/ng-template">
    <p><input ng-model="type.name">
    <p><input ng-model="type.color">
    <p><input ng-model="type.priority">


    <a href="#list"> 返回 </a>
    <button ng-click="save(type)"> 保存</button>

</script>