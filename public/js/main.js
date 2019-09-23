const articles = document.getElementById("articles");// get table of articles

if (articles)
{
    articles.addEventListener("click" , (e) => {
        //have many delete button, so identify what button clicked
        if (e.target.className === "btn delete-article")
        {
            if (confirm("Are you sure?"))
            {
                const id = e.target.getAttribute("data-id");

                //already had id, fetch request to the backend
                fetch(`/article/delete/${id}`,{
                    method: 'DELETE'
                }).then(result => window.location.reload());
            }
        }
    });
}