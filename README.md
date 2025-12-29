# Blog-Management-System

backend (Laravel API)

blog-management-api/
├── app/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Post.php
│   │   ├── Tag.php
│   │   ├── Comment.php
│   │   ├── Like.php
│   │   └── Bookmark.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── PostController.php
│   │   │   ├── CommentController.php
│   │   │   └── InteractionController.php
│   │   └── Resources/
│   │       └── PostResource.php
│   └── Providers/
├── database/
│   ├── migrations/
│   └── seeders/
├── routes/
│   └── api.php
└── config/
    └── sanctum.php

Frontend (Vue.js3 + Vite)

blog-management-frontend/
├── src/
│   ├── components/
│   │   ├── auth/
│   │   │   ├── Login.vue
│   │   │   ├── Register.vue
│   │   │   └── ForgotPassword.vue
│   │   ├── posts/
│   │   │   ├── PostList.vue
│   │   │   ├── PostCreate.vue
│   │   │   ├── PostEdit.vue
│   │   │   └── PostDetail.vue
│   │   ├── comments/
│   │   │   ├── CommentSection.vue
│   │   │   └── CommentItem.vue
│   │   └── shared/
│   │       ├── Navbar.vue
│   │       └── Sidebar.vue
│   ├── views/
│   │   ├── Home.vue
│   │   ├── Dashboard.vue
│   │   ├── Profile.vue
│   │   └── Bookmarks.vue
│   ├── router/
│   │   └── index.js
│   ├── store/
│   │   └── index.js (Vuex/Pinia)
│   ├── services/
│   │   ├── api.js
│   │   └── auth.js
│   ├── utils/
│   │   └── helpers.js
│   └── App.vue
├── public/
└── package.json
