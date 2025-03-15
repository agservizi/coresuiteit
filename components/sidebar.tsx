"use client"

import Link from "next/link"
import { usePathname } from "next/navigation"
import { cn } from "@/lib/utils"
import { Button } from "@/components/ui/button"
import {
  LayoutDashboard,
  Package,
  ShoppingCart,
  Users,
  Truck,
  BarChart,
  Settings,
  LogOut,
  Menu,
  ChevronLeft,
  ChevronRight,
} from "lucide-react"
import { useState, useEffect } from "react"
import { Sheet, SheetContent, SheetTrigger } from "@/components/ui/sheet"
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar"

export default function Sidebar() {
  const pathname = usePathname()
  const [isMobileOpen, setIsMobileOpen] = useState(false)
  const [isCollapsed, setIsCollapsed] = useState(false)

  // Controlla la dimensione dello schermo per impostare lo stato iniziale su mobile
  useEffect(() => {
    const handleResize = () => {
      if (window.innerWidth < 768) {
        setIsCollapsed(true)
      }
    }

    // Imposta lo stato iniziale
    handleResize()

    // Aggiungi event listener per il resize
    window.addEventListener("resize", handleResize)

    // Cleanup
    return () => window.removeEventListener("resize", handleResize)
  }, [])

  const routes = [
    {
      href: "/",
      icon: LayoutDashboard,
      title: "Dashboard",
    },
    {
      href: "/inventory",
      icon: Package,
      title: "Magazzino",
    },
    {
      href: "/sales",
      icon: ShoppingCart,
      title: "Vendite",
    },
    {
      href: "/customers",
      icon: Users,
      title: "Clienti",
    },
    {
      href: "/suppliers",
      icon: Truck,
      title: "Fornitori",
    },
    {
      href: "/reports",
      icon: BarChart,
      title: "Reportistica",
    },
    {
      href: "/settings",
      icon: Settings,
      title: "Impostazioni",
    },
  ]

  // Componente per il contenuto della sidebar
  const SidebarContent = () => (
    <div className="flex h-full flex-col gap-2 bg-gradient-sidebar text-sidebar-foreground">
      <div className="flex h-16 items-center border-b border-white/10 px-4 justify-between">
        {!isCollapsed && (
          <Link href="/" className="flex items-center gap-2 font-semibold">
            <Package className="h-6 w-6" />
            <span className="text-lg">TeleStore</span>
          </Link>
        )}
        <Button
          variant="ghost"
          size="icon"
          onClick={() => setIsCollapsed(!isCollapsed)}
          className={cn(
            "text-white/70 hover:text-white hover:bg-white/10",
            isCollapsed ? "ml-auto mr-auto" : "ml-auto",
          )}
        >
          {isCollapsed ? <ChevronRight className="h-5 w-5" /> : <ChevronLeft className="h-5 w-5" />}
        </Button>
      </div>
      <div className="flex-1 overflow-y-auto py-4 scrollbar-thin">
        <nav className="grid items-start px-2 text-sm font-medium">
          {routes.map((route) => (
            <Link
              key={route.href}
              href={route.href}
              className={cn(
                "flex items-center gap-3 rounded-lg px-3 py-2.5 my-1 transition-all sidebar-item",
                pathname === route.href ? "bg-white/20 text-white" : "text-white/70 hover:text-white",
                isCollapsed && "justify-center px-2",
              )}
            >
              <route.icon className="h-5 w-5" />
              {!isCollapsed && <span>{route.title}</span>}
            </Link>
          ))}
        </nav>
      </div>
      <div className="mt-auto border-t border-white/10 p-4">
        <div className={cn("flex items-center gap-3 py-2 px-2 rounded-lg bg-white/5", isCollapsed && "flex-col")}>
          <Avatar className="h-10 w-10 border-2 border-white/20">
            <AvatarImage src="/placeholder.svg?height=36&width=36" alt="Avatar" />
            <AvatarFallback>AD</AvatarFallback>
          </Avatar>
          {!isCollapsed && (
            <div className="grid gap-0.5 text-sm">
              <div className="font-medium">Admin</div>
              <div className="text-xs text-white/70">admin@telestore.it</div>
            </div>
          )}
          {!isCollapsed && (
            <Button variant="ghost" size="icon" className="ml-auto text-white/70 hover:text-white hover:bg-white/10">
              <LogOut className="h-4 w-4" />
              <span className="sr-only">Logout</span>
            </Button>
          )}
        </div>
      </div>
    </div>
  )

  return (
    <>
      {/* Sidebar desktop - ora fissa */}
      <aside
        className={cn(
          "hidden md:block fixed top-0 left-0 h-screen z-30 transition-all duration-300 ease-in-out",
          isCollapsed ? "w-16" : "w-64",
        )}
      >
        <SidebarContent />
      </aside>

      {/* Header mobile con hamburger menu */}
      <div className="fixed left-0 right-0 top-0 z-20 flex h-16 items-center gap-2 border-b bg-background px-4 md:hidden">
        <Sheet open={isMobileOpen} onOpenChange={setIsMobileOpen}>
          <SheetTrigger asChild>
            <Button variant="outline" size="icon">
              <Menu className="h-5 w-5" />
              <span className="sr-only">Toggle Menu</span>
            </Button>
          </SheetTrigger>
          <SheetContent side="left" className="w-64 p-0">
            <SidebarContent />
          </SheetContent>
        </Sheet>
        <div className="flex items-center gap-2">
          <Package className="h-6 w-6" />
          <span className="font-semibold">TeleStore Manager</span>
        </div>
      </div>

      {/* Spazio per l'header mobile */}
      <div className="h-16 md:hidden" />

      {/* Spazio per compensare la sidebar fissa su desktop */}
      <div
        className="hidden md:block md:w-16 md:flex-shrink-0 md:transition-all md:duration-300 ease-in-out"
        style={{ width: isCollapsed ? "4rem" : "16rem" }}
      />
    </>
  )
}

