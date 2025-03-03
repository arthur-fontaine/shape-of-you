<script lang="ts">
  const menus = [
    { icon: "icon-[tabler--home]", link: "/", main: true, name: "Accueil" },
    {
      icon: "icon-[tabler--search]",
      link: "/search",
      main: false,
      name: "Recherche",
    },
    {
      icon: "icon-[tabler--shopping-bag]",
      link: "/buy",
      main: false,
      name: "Acheter",
    },
    {
      icon: "icon-[tabler--user]",
      link: "/profile",
      main: false,
      name: "Profil",
    },
  ];

  let forceActive = $state<string | undefined>(undefined);

  const isActive = (link: string) => {
    if (forceActive === undefined) {
      return link === window.location.pathname;
    }
    return link === forceActive;
  };
</script>

<nav class="fixed bottom-0 left-0 w-full flex flex-col-reverse gap-6">
  <div class="border-t-border border-t-1">
    <ul class="flex gap-4 justify-around bg-background py-2 px-4">
      {#each menus as menuItem}
        <li
          class={`flex-1 ${isActive(menuItem.link) ? "text-label" : "text-disabled"}`}
        >
          <a href={menuItem.link} onclick={() => (forceActive = menuItem.link)}>
            <div class="w-8 h-12 flex items-center flex-col gap-1 mx-auto">
              <span class={`${menuItem.icon} text-2xl`}></span>
              <span class="text-xs">
                {menuItem.name}
              </span>
            </div>
          </a>
        </li>
      {/each}
    </ul>
  </div>

  <a
    href="/posts/new"
    class="ui w-fit h-fit p-4 rounded-button flex self-end mx-6"
    aria-label="Créer un post"
  >
    <span class="icon-[tabler--plus] text-2xl text-white"></span>
  </a>
</nav>
