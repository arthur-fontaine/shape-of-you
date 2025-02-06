import { createQuery as createQuery_, createMutation as createMutation_, QueryClient, setQueryClientContext, getQueryClientContext } from '@tanstack/svelte-query'

function initQueryClient() {
  let queryClientDefined = false;
  try {
    getQueryClientContext();
    queryClientDefined = true;
  } catch (e) {
    queryClientDefined = false;
  }

  if (!queryClientDefined) {
    const queryClient = new QueryClient();
    setQueryClientContext(queryClient);
  }
}

export const createQuery = <T>(url: string = location.href) => {
  initQueryClient();
  return createQuery_({
    queryKey: [url],
    queryFn: async () => {
      const res = await fetch(url)
      return res.json() as Promise<T>
    },
  })
}

export const createMutation = <T, U>(url: string = location.href) => {
  initQueryClient();
  return createMutation_({
    mutationKey: [url],
    mutationFn: async (data: U) => {
      const res = await fetch(url, {
        method: 'POST',
        body: data instanceof FormData ? data : JSON.stringify(data),
        headers: {
          ...data instanceof FormData ? {} : { 'Content-Type': 'application/json' },
        },
      })
      return res.json() as Promise<T>
    },
  })
}
