<script lang="ts">
  import Search from "../../../icons/search.svg";
  import Menu from "../../../icons/menu.svg";
  import Plus from "../../../icons/plus.svg";
  import Profile from "../../../icons/profile.svg";
  import { onMount } from "svelte";

  let props: { showHome?: boolean } = $props();
  let isExpanded = $state(false);

  let container: HTMLDivElement;

  const menus = [
    props.showHome ? { icon: null, link: "/", main: true } : null,
    { icon: Search, link: "/search", main: props.showHome ? false : true },
    { icon: Profile, link: "/profile" },
    { icon: Plus, link: "/posts/new" },
  ].filter((m) => m !== null);

  function clickOnMainButton(e: MouseEvent) {
    e.stopPropagation();
    if (isExpanded) {
      window.location.href = menus.find((m) => m.main)?.link ?? '/';
    } else {
      isExpanded = true;
    }
  }

  const mainButtonComponent = $derived(isExpanded ? "a" : "button");

  onMount(() => {
    window.addEventListener("click", (e) => {
      if (isExpanded && !container.contains(e.target as Node)) {
        isExpanded = false;
      } else {
        e.stopPropagation();
      }
    });
  });
</script>

<div class="flex flex-col-reverse items-center fixed bottom-4 right-4 gap-2" bind:this={container}>
  <svelte:element
    this={mainButtonComponent}
    class="bg-gray-900 text-white rounded-full w-16 h-16 flex items-center justify-center"
    onclick={clickOnMainButton}
    role="button"
    tabindex="-1"
    href={isExpanded ? menus.find((m) => m.main)?.link : undefined}
  >
    {#if isExpanded}
      <img class="invert" src={menus.find((m) => m.main)?.icon} alt={menus.find((m) => m.main)?.link} />
    {:else}
      <img class="invert" src={Menu} alt="Menu" />
    {/if}
  </svelte:element>
  {#if isExpanded}
    {#each menus as menuItem}
      {#if !menuItem.main}
        <a
          href={menuItem.link}
          class="block bg-gray-100 text-gray-900 w-12 h-12 rounded-full flex items-center justify-center"
        >
          <img src={menuItem.icon} alt={menuItem.link} />
        </a>
      {/if}
    {/each}
  {/if}
</div>
