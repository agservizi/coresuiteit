"use client"

import { useEffect, useState } from "react"
import { useStore } from "@/lib/store"
import { Moon, Sun, Laptop } from "lucide-react"
import { Button } from "@/components/ui/button"
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from "@/components/ui/dropdown-menu"

export function ThemeToggle() {
  const { settings, updateSettings } = useStore()
  const [mounted, setMounted] = useState(false)

  // Evita errori di idratazione
  useEffect(() => {
    setMounted(true)
  }, [])

  // Applica il tema quando cambia
  useEffect(() => {
    if (!mounted) return

    const applyTheme = () => {
      const theme = settings?.theme || "light"

      if (theme === "dark") {
        document.documentElement.classList.add("dark")
      } else if (theme === "light") {
        document.documentElement.classList.remove("dark")
      } else {
        // System
        if (window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches) {
          document.documentElement.classList.add("dark")
        } else {
          document.documentElement.classList.remove("dark")
        }
      }
    }

    applyTheme()

    // Listener per il tema di sistema
    if (settings?.theme === "system") {
      const mediaQuery = window.matchMedia("(prefers-color-scheme: dark)")
      const handleChange = () => applyTheme()
      mediaQuery.addEventListener("change", handleChange)
      return () => mediaQuery.removeEventListener("change", handleChange)
    }
  }, [settings?.theme, mounted])

  if (!mounted) {
    return (
      <Button variant="outline" size="icon" disabled>
        <Sun className="h-5 w-5" />
      </Button>
    )
  }

  const currentTheme = settings?.theme || "light"

  return (
    <DropdownMenu>
      <DropdownMenuTrigger asChild>
        <Button variant="outline" size="icon">
          {currentTheme === "light" && <Sun className="h-5 w-5" />}
          {currentTheme === "dark" && <Moon className="h-5 w-5" />}
          {currentTheme === "system" && <Laptop className="h-5 w-5" />}
          <span className="sr-only">Cambia tema</span>
        </Button>
      </DropdownMenuTrigger>
      <DropdownMenuContent align="end">
        <DropdownMenuItem onClick={() => updateSettings({ theme: "light" })}>
          <Sun className="mr-2 h-4 w-4" />
          <span>Chiaro</span>
        </DropdownMenuItem>
        <DropdownMenuItem onClick={() => updateSettings({ theme: "dark" })}>
          <Moon className="mr-2 h-4 w-4" />
          <span>Scuro</span>
        </DropdownMenuItem>
        <DropdownMenuItem onClick={() => updateSettings({ theme: "system" })}>
          <Laptop className="mr-2 h-4 w-4" />
          <span>Sistema</span>
        </DropdownMenuItem>
      </DropdownMenuContent>
    </DropdownMenu>
  )
}

