function sort(field, order) 
{
    let url = new URL(window.location.href);
    let query_string = url.search;
    let search_params = new URLSearchParams(query_string);

    search_params.set('orderby', `${field} ${order}`);
    url.search = search_params.toString();

    let new_url = url.toString();
    window.location.href = new_url;
}

function deleteButton(row_id)
{
    
}

function editButton(row_id)
{
    $("#action-button").text("Update");
    $("#input-row").find("input").each(function(index,value)
    {
        $(this).attr("style", "");
    });
}

function openCreate()
{
    $("#action-button").text("Enter");
    $("#input-row").find("input").each(function(index,value)
    {
        $(this).attr("style", "");
    });
}

function submitCreate()
{
    $("#action-button").submit();
}