<template>
  <ul class="breadcrumb">
    <template v-for="(page,index) in pages">
      <li v-if="!page.active" class="breadcrumb-item" :key="index">
        <i v-if="page.icon" :class="page.icon"></i>
        <router-link :to="page.link">{{$t(page.name)}}</router-link>
      </li>
      <li v-else class="breadcrumb-item active" :key="index">
        <i v-if="page.icon" :class="page.icon"></i>
        {{$t(page.name)}}
      </li>
    </template>
  </ul>
</template>

<script>
export default {
  name: "breadcrumb",
  props: ["pages"]
};
</script>
