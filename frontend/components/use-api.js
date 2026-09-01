'use client';

import { useCallback, useEffect, useState } from 'react';
import { api } from '../lib/api';

export function useApi(path) {
  const [data, setData] = useState(null); const [error, setError] = useState(''); const [loading, setLoading] = useState(true);
  const reload = useCallback(async () => { setLoading(true); try { setData(await api(path)); setError(''); } catch (requestError) { setError(requestError.message); } finally { setLoading(false); } }, [path]);
  useEffect(() => { reload(); }, [reload]);
  return { data, error, loading, reload };
}
