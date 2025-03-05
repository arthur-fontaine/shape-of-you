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
      childrenMeansActive: true,
    },
  ];

  let forceActive = $state<string | undefined>(undefined);

  const isActive = (link: string) => {
    if (forceActive === undefined) {
      const menuLink = menus.find((menu) => menu.link === link);
      if (menuLink?.childrenMeansActive)
        return location.pathname.startsWith(link);
      return location.pathname === link;
    }
    return link === forceActive;
  };
</script>

<nav
  class="fixed bottom-0 left-0 w-full flex flex-col-reverse gap-6 pointer-events-none"
>
  <div class="border-t-border border-t-1 pointer-events-auto">
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
    class="ui w-fit h-fit p-4 rounded-button flex self-end mx-6 pointer-events-auto"
    aria-label="Créer un post"
  >
    <span class="icon-[tabler--plus] text-2xl text-white"></span>
  </a>
</nav>
