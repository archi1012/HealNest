'use client';

import Link from 'next/link';
import { usePathname, useRouter } from 'next/navigation';
import { api } from '../lib/api';

const links = [
  ['Dashboard', '/dashboard'], ['Profile', '/profile'], ['Log Mood', '/mood'], ['Appointments', '/appointments'], ['Messages', '/messages'], ['Assessment', '/assessment'], ['Resources', '/resources'], ['Counselors', '/counselor'],
];

export default function AppShell({ title, children }) {
  const pathname = usePathname(); const router = useRouter();
  async function logout() { await api('/auth/logout', { method: 'POST' }); router.replace('/login'); }
  return <div className="min-h-screen bg-cream text-forest md:flex"><aside className="bg-forest p-5 text-white md:min-h-screen md:w-64"><Link className="font-heading text-2xl font-bold text-tan" href="/dashboard">🌿 HealNest</Link><nav className="mt-8 space-y-1">{links.map(([label, href]) => <Link key={href} href={href} className={`block rounded px-3 py-2 text-sm ${pathname === href ? 'bg-midgreen' : 'hover:bg-midgreen'}`}>{label}</Link>)}</nav><button onClick={logout} className="mt-8 text-sm text-tan">Logout</button></aside><main className="flex-1 p-6"><h1 className="font-heading text-2xl font-semibold">{title}</h1><div className="mt-6">{children}</div></main></div>;
}
