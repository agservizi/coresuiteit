"use client"

import type React from "react"

import { createContext, useContext, useState, useEffect } from "react"

type SidebarContextType = {
  isOpen: boolean
  isCollapsed: boolean
  toggle: () => void
  collapse: () => void
  expand: () => void
}

const SidebarContext = createContext<SidebarContextType | undefined>(undefined)

export function SidebarProvider({ children }: { children: React.ReactNode }) {
  const [isOpen, setIsOpen] = useState(false)
  const [isCollapsed, setIsCollapsed] = useState(false)

  // Check if we're on a mobile device on initial render
  useEffect(() => {
    const checkScreenSize = () => {
      if (window.innerWidth < 768) {
        setIsOpen(false)
      } else {
        setIsOpen(true)
        setIsCollapsed(false)
      }
    }

    // Set initial state
    checkScreenSize()

    // Add event listener for window resize
    window.addEventListener("resize", checkScreenSize)

    // Cleanup
    return () => window.removeEventListener("resize", checkScreenSize)
  }, [])

  const toggle = () => {
    setIsOpen(!isOpen)
  }

  const collapse = () => {
    if (window.innerWidth >= 768) {
      setIsCollapsed(true)
    }
  }

  const expand = () => {
    if (window.innerWidth >= 768) {
      setIsCollapsed(false)
    }
  }

  return (
    <SidebarContext.Provider value={{ isOpen, isCollapsed, toggle, collapse, expand }}>
      {children}
    </SidebarContext.Provider>
  )
}

export function useSidebar() {
  const context = useContext(SidebarContext)
  if (context === undefined) {
    throw new Error("useSidebar must be used within a SidebarProvider")
  }
  return context
}

