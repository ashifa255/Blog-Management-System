const buildCommentTree = (comments) => {
  const commentMap = {}
  const rootComments = []
  
  // First pass: create a map
  comments.forEach(comment => {
    commentMap[comment.id] = { ...comment, replies: [] }
  })
  
  // Second pass: build tree
  comments.forEach(comment => {
    if (comment.parent_id) {
      if (commentMap[comment.parent_id]) {
        commentMap[comment.parent_id].replies.push(commentMap[comment.id])
      }
    } else {
      rootComments.push(commentMap[comment.id])
    }
  })
  
  return rootComments
}
