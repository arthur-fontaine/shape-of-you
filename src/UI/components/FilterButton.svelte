<script lang="ts">
  export let label: string;
  export let active: boolean = false;
  export let onClick: () => void;
</script>

<button 
  type="button"
  class="button secondary flex items-center py-2 px-3 text-xs rounded-chip"
  onclick={onClick}
>
  <span class="text-trim-both">{label}</span>
  <span class="icon-[tabler--chevron-down] ml-1 text-trim-both"></span>
</button>