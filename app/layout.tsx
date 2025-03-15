import "@/app/globals.css"
import { Inter } from "next/font/google"
import { ThemeProvider } from "@/components/theme-provider"
import Sidebar from "@/components/sidebar"
import Navbar from "@/components/navbar"
import { Toaster } from "@/components/toaster"
import { NotificationsManager } from "@/components/notifications-manager"

const inter = Inter({ subsets: ["latin"] })

export const metadata = {
  title: "Gestione Negozio Telefonia",
  description: "Web app per la gestione di un negozio di telefonia",
    generator: 'v0.dev'
}

export default function RootLayout({ children }) {
  return (
    <html lang="it">
      <body className={inter.className}>
        <ThemeProvider attribute="class" defaultTheme="light" enableSystem>
          <div className="flex min-h-screen">
            <Sidebar />
            <div className="flex-1 flex flex-col transition-all duration-300 ease-in-out">
              <Navbar />
              <div className="flex-1">{children}</div>
            </div>
          </div>
          <NotificationsManager />
          <Toaster />
        </ThemeProvider>
      </body>
    </html>
  )
}



import './globals.css'