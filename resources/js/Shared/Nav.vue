<template>
    <nav>
        <ul>
        <li><a href="/">Home</a></li>
        <li><a href="/about">About</a></li>
        <li><a href="/contact">Contact</a></li>
        <li><a href="/blog">Blog</a></li>
        </ul>
    </nav>
</template>

<script>
import { Link } from '@inertiajs/vue3'
export default{
 components: { Link },
};
</script>