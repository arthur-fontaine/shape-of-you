<script lang="ts">
</script>

<div class="flex gap-4">
  <div class="size-20 rounded-xl overflow-hidden">
    <slot name="image" />
  </div>
  <div class="flex-1 flex flex-col">
    <h3 class="text-base font-semibold">
      <slot name="name" />
    </h3>
    <p class="text-base font-normal">
      <slot name="description" />
    </p>
  </div>
</div>
