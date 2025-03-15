"use client"

import { useSidebar } from "./sidebar-context"
import { Button } from "./ui/button"
import { Menu } from "lucide-react"
import { ModeToggle } from "./mode-toggle"

export default function Header() {
  const { toggle, isOpen } = useSidebar()

  return (
    <header className="sticky top-0 z-30 flex h-16 items-center gap-4 border-b bg-background px-4 md:px-6">
      <Button variant="ghost" size="icon" onClick={toggle} className="md:hidden">
        <Menu className="h-5 w-5" />
        <span className="sr-only">Toggle Menu</span>
      </Button>

      <div className="flex-1 md:ml-auto flex justify-between">
        <div className="flex items-center gap-2 md:hidden">
          <span className="text-primary font-semibold text-lg">Acme Inc.</span>
        </div>

        <div className="flex items-center gap-4">
          <ModeToggle />

          <Button variant="ghost" size="icon" className="rounded-full">
            <img src="https://github.com/shadcn.png" alt="Avatar" className="rounded-full" height={32} width={32} />
            <span className="sr-only">Toggle user menu</span>
          </Button>
        </div>
      </div>
    </header>
  )
}

