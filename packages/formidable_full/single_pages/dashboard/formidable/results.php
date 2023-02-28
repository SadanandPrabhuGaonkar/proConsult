<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<div class="ccm-dashboard-content-full" data-search="formidable_results">

    <?php if ($result) { $list = $result->getItemListObject();?>

        <script type="text/template" data-template="search-results-table-body">
            <% _.each(items, function (item) {%>
            <tr data-launch-search-menu="<%=item.answerSetID%>">
                <td>#<%=item.answerSetID%></td>
                <% for (i = 0; i < item.columns.length; i++) {
                var column = item.columns[i]; %>
                    <% if (i == 0) { %>
                    <td class="ccm-search-results-name"><%-column.value%></td>
                    <% } else { %>
                    <td><%-column.value%></td>
                    <% } %>
                <% } %>
            </tr>
            <% }); %>
        </script>

        <script type="text/template" data-template="search-results-table-head">
            <tr>
                <th>
                    <div class="dropdown">
                        <button class="btn btn-menu-launcher" disabled data-toggle="dropdown"><i class="fa fa-chevron-down"></i></button>
                    </div>
                </th>
                <%
                for (i = 0; i < columns.length; i++) {
                var column = columns[i];
                if (column.isColumnSortable) { %>
                <th class="<%=column.className%>"><a href="<%=column.sortURL%>"><%-column.title%></a></th>
                <% } else { %>
                <th><span><%-column.title%></span></th>
                <% } %>
                <% } %>
            </tr>
        </script>

        <div data-search-element="wrapper"></div>

        <div data-search-element="results">
            <div class="table-responsive">
                <table class="ccm-results-list ccm-search-results-table ccm-search-results-table-icon">
                <thead>
                </thead>
                <tbody>
                </tbody>
                </table>
            </div>
            <div class="ccm-search-results-pagination"></div>
        </div>

        <script type="text/template" data-template="search-results-pagination">
        <%=paginationTemplate%>
        </script>

    <?php } else { ?>
        
        <table class="entry ccm-search-results-table">
            <thead>
                <tr>
                    <th><span><?php echo t('Results'); ?></span></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td align="center"><?php echo t('No formidable forms created. Please create a form'); ?></td>
                </tr>
            </tbody>
        </table>
    <?php } ?>
    
</div>