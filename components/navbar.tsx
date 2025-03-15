"use client"

import { useState, useEffect } from "react"
import { usePathname } from "next/navigation"
import Link from "next/link"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Search, Package, Users, Truck, Plus } from "lucide-react"
import { useStore } from "@/lib/store"
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from "@/components/ui/dropdown-menu"
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar"
import { ThemeToggle } from "@/components/theme-toggle"
import { NotificationsCenter } from "@/components/notifications-center"

export default function Navbar() {
  const pathname = usePathname()
  const [searchQuery, setSearchQuery] = useState("")
  const [searchResults, setSearchResults] = useState([])
  const [showResults, setShowResults] = useState(false)
  const {
    products,
    customers,
    sales,
    suppliers,
    notifications,
    markNotificationAsRead,
    markAllNotificationsAsRead,
    getUnreadNotificationsCount,
    settings,
    updateSettings,
  } = useStore()

  // Determina quali pulsanti di azione mostrare in base al percorso
  const getActionButton = () => {
    switch (true) {
      case pathname === "/" || pathname.startsWith("/sales"):
        return (
          <Link
            href="/sales/new"
            className="flex items-center gap-2 h-9 px-4 rounded-md border border-input bg-background text-foreground hover:bg-accent hover:text-accent-foreground shadow-sm"
          >
            <Plus className="h-4 w-4" />
            <span className="inline-block whitespace-nowrap">Nuova Vendita</span>
          </Link>
        )
      case pathname.startsWith("/inventory"):
        return (
          <Link
            href="/inventory/add"
            className="flex items-center gap-2 h-9 px-4 rounded-md border border-input bg-background text-foreground hover:bg-accent hover:text-accent-foreground shadow-sm"
          >
            <Package className="h-4 w-4" />
            <span className="inline-block whitespace-nowrap">Aggiungi Prodotto</span>
          </Link>
        )
      case pathname.startsWith("/customers"):
        return (
          <Link
            href="/customers/new"
            className="flex items-center gap-2 h-9 px-4 rounded-md border border-input bg-background text-foreground hover:bg-accent hover:text-accent-foreground shadow-sm"
          >
            <Users className="h-4 w-4" />
            <span className="inline-block whitespace-nowrap">Nuovo Cliente</span>
          </Link>
        )
      case pathname.startsWith("/suppliers"):
        return (
          <Link
            href="/suppliers/new"
            className="flex items-center gap-2 h-9 px-4 rounded-md border border-input bg-background text-foreground hover:bg-accent hover:text-accent-foreground shadow-sm"
          >
            <Truck className="h-4 w-4" />
            <span className="inline-block whitespace-nowrap">Nuovo Fornitore</span>
          </Link>
        )
      default:
        return null
    }
  }

  // Funzione di ricerca globale
  const performSearch = (query) => {
    if (!query.trim()) {
      setSearchResults([])
      setShowResults(false)
      return
    }

    const lowerQuery = query.toLowerCase()

    // Cerca nei prodotti
    const productResults = products
      .filter((p) => p.name.toLowerCase().includes(lowerQuery) || p.category.toLowerCase().includes(lowerQuery))
      .slice(0, 3)
      .map((p) => ({
        id: `product-${p.id}`,
        type: "product",
        title: p.name,
        subtitle: `${p.category} - ${p.price.toFixed(2)}€`,
        link: `/inventory/edit/${p.id}`,
      }))

    // Cerca nei clienti
    const customerResults = customers
      .filter(
        (c) =>
          c.name.toLowerCase().includes(lowerQuery) ||
          c.email.toLowerCase().includes(lowerQuery) ||
          c.phone.toLowerCase().includes(lowerQuery),
      )
      .slice(0, 3)
      .map((c) => ({
        id: `customer-${c.id}`,
        type: "customer",
        title: c.name,
        subtitle: c.email,
        link: `/customers/${c.id}`,
      }))

    // Cerca nelle vendite
    const saleResults = sales
      .filter(
        (s) => s.customer.name.toLowerCase().includes(lowerQuery) || s.provider.toLowerCase().includes(lowerQuery),
      )
      .slice(0, 3)
      .map((s) => ({
        id: `sale-${s.id}`,
        type: "sale",
        title: `Vendita #${s.id}`,
        subtitle: `${s.customer.name} - ${s.total.toFixed(2)}€`,
        link: `/sales/${s.id}`,
      }))

    // Cerca nei fornitori
    const supplierResults = suppliers
      .filter((s) => s.name.toLowerCase().includes(lowerQuery) || s.products.toLowerCase().includes(lowerQuery))
      .slice(0, 3)
      .map((s) => ({
        id: `supplier-${s.id}`,
        type: "supplier",
        title: s.name,
        subtitle: s.products,
        link: `/suppliers/${s.id}`,
      }))

    // Combina i risultati
    const allResults = [...productResults, ...customerResults, ...saleResults, ...supplierResults].slice(0, 10)

    setSearchResults(allResults)
    setShowResults(true)
  }

  // Gestisce il cambio di input nella ricerca
  const handleSearchChange = (e) => {
    const query = e.target.value
    setSearchQuery(query)
    performSearch(query)
  }

  // Chiude i risultati quando si clicca fuori
  useEffect(() => {
    const handleClickOutside = () => {
      setShowResults(false)
    }

    document.addEventListener("click", handleClickOutside)
    return () => {
      document.removeEventListener("click", handleClickOutside)
    }
  }, [])

  // Notifiche non lette
  const unreadNotificationsCount = getUnreadNotificationsCount()

  return (
    <div className="sticky top-0 z-30 flex h-16 items-center gap-4 border-b bg-background px-4 md:px-6 shadow-sm">
      <div className="flex flex-1 items-center gap-4">
        {/* Barra di ricerca globale */}
        <div className="relative w-full max-w-sm">
          <Search className="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
          <Input
            type="search"
            placeholder="Cerca prodotti, clienti, vendite..."
            className="w-full pl-8 md:w-[300px] lg:w-[400px]"
            value={searchQuery}
            onChange={handleSearchChange}
            onClick={(e) => {
              e.stopPropagation()
              if (searchQuery && searchResults.length > 0) {
                setShowResults(true)
              }
            }}
          />

          {/* Risultati della ricerca */}
          {showResults && searchResults.length > 0 && (
            <div className="absolute top-full left-0 mt-1 w-full bg-background rounded-md border shadow-lg z-50">
              <div className="p-2">
                <h4 className="text-sm font-medium text-muted-foreground mb-2">Risultati della ricerca</h4>
                <div className="space-y-1">
                  {searchResults.map((result) => (
                    <Link
                      key={result.id}
                      href={result.link}
                      className="flex items-center p-2 rounded-md hover:bg-muted transition-colors"
                      onClick={() => setShowResults(false)}
                    >
                      <div>
                        <div className="text-sm font-medium">{result.title}</div>
                        <div className="text-xs text-muted-foreground">{result.subtitle}</div>
                      </div>
                    </Link>
                  ))}
                </div>
              </div>
            </div>
          )}
        </div>
      </div>

      {/* Pulsanti di azione contestuali */}
      <div className="flex items-center gap-2">
        {getActionButton()}

        {/* Notifiche */}
        <NotificationsCenter />

        {/* Selettore tema */}
        <ThemeToggle />

        {/* Menu utente */}
        <DropdownMenu>
          <DropdownMenuTrigger asChild>
            <Button variant="ghost" size="icon" className="rounded-full">
              <Avatar className="h-8 w-8">
                <AvatarImage src="/placeholder.svg?height=32&width=32" alt="Avatar" />
                <AvatarFallback>AD</AvatarFallback>
              </Avatar>
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent align="end">
            <DropdownMenuItem asChild>
              <Link href="/settings">Impostazioni</Link>
            </DropdownMenuItem>
            <DropdownMenuItem asChild>
              <Link href="/profile">Profilo</Link>
            </DropdownMenuItem>
            <DropdownMenuItem>Esci</DropdownMenuItem>
          </DropdownMenuContent>
        </DropdownMenu>
      </div>
    </div>
  )
}

