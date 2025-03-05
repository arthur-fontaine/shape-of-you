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

export const createMutation = <T = unknown, U = void>(
  _url: string | undefined = undefined,
  _method: 'POST' | 'PUT' | 'PATCH' | 'DELETE' | undefined = undefined,
  { onSuccess, onMutate, onError }: {
    onSuccess?: (data: T, params: U) => void,
    onMutate?: (params: U) => void,
    onError?: (error: any, params: U) => void,
  } = {}
) => {
  const url = _url ?? location.href;
  const method = _method ?? 'POST';

  initQueryClient();
  return createMutation_({
    mutationKey: [url],
    mutationFn: async (data: U) => {
      const res = await fetch(url, {
        method,
        body: data instanceof FormData ? data : JSON.stringify(data),
        headers: {
          ...data instanceof FormData ? {} : { 'Content-Type': 'application/json' },
        },
      })
      return res.json() as Promise<T>
    },
    onSuccess,
    onMutate,
    onError,
  })
}
