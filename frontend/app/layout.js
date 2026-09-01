import './globals.css';

export const metadata = {
  title: 'HealNest',
  description: 'Mental wellness support platform',
};

export default function RootLayout({ children }) {
  return (
    <html lang="en">
      <body>{children}</body>
    </html>
  );
}
