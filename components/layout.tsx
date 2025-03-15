"use client"

import type React from "react"

import { useSidebar } from "./sidebar-context"
import Sidebar from "./sidebar"
import Header from "./header"
import { cn } from "@/lib/utils"

export default function Layout({ children }: { children: React.ReactNode }) {
  const { isOpen, isCollapsed } = useSidebar()

  return (
    <div className="min-h-screen flex flex-col">
      <Header />
      <div className="flex flex-1 overflow-hidden">
        <Sidebar />
        <main
          className={cn(
            "flex-1 overflow-y-auto transition-all duration-300 ease-in-out",
            isOpen && !isCollapsed ? "md:ml-64" : isOpen && isCollapsed ? "md:ml-20" : "",
          )}
        >
          <div className="container mx-auto py-6">{children}</div>
        </main>
      </div>
    </div>
  )
}

