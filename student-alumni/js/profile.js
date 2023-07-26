function toggleLike() {
    var likeIcon = document.getElementById('likeIcon');
    likeIcon.classList.toggle('fas'); // Toggle the 'fas' class (solid heart)
    likeIcon.classList.toggle('far'); // Toggle the 'far' class (regular heart)
    likeIcon.classList.toggle('text-red-600'); // Toggle the color to red
}

function toggleComment() {
    var commentIcon = document.getElementById('commentIcon');
    commentIcon.classList.toggle('fas'); // Toggle the 'fas' class (solid comment)
    commentIcon.classList.toggle('far'); // Toggle the 'far' class (regular comment)
    commentIcon.classList.toggle('text-blue-600'); // Toggle the color to blue
}