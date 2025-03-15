"use client"

import { useEffect } from "react"
import { useStore } from "@/lib/store"
import { toast } from "@/hooks/use-toast"

export function NotificationsManager() {
  const { notifications, markNotificationAsRead, getUnreadNotificationsCount } = useStore()

  // Richiedi il permesso per le notifiche del browser all'avvio
  useEffect(() => {
    if ("Notification" in window && Notification.permission !== "granted" && Notification.permission !== "denied") {
      // Richiedi il permesso quando l'utente interagisce con la pagina
      const requestPermission = () => {
        Notification.requestPermission()
        // Rimuovi l'event listener dopo la richiesta
        document.removeEventListener("click", requestPermission)
      }
      document.addEventListener("click", requestPermission, { once: true })
    }
  }, [])

  // Mostra un toast per le nuove notifiche non lette
  useEffect(() => {
    const unreadNotifications = notifications.filter((n) => !n.read)

    if (unreadNotifications.length > 0) {
      // Mostra solo la notifica più recente come toast
      const latestNotification = unreadNotifications[0]

      // Mostra il toast con il tipo appropriato
      toast({
        title: latestNotification.title,
        description: latestNotification.message,
        variant:
          latestNotification.type === "error"
            ? "destructive"
            : latestNotification.type === "success"
              ? "success"
              : "default",
      })

      // Segna la notifica come letta dopo che è stata mostrata
      setTimeout(() => {
        markNotificationAsRead(latestNotification.id)
      }, 5000)
    }
  }, [notifications, markNotificationAsRead])

  // Aggiorna il titolo della pagina con il conteggio delle notifiche non lette
  useEffect(() => {
    const unreadCount = getUnreadNotificationsCount()
    if (unreadCount > 0) {
      document.title = `(${unreadCount}) TeleStore Manager`
    } else {
      document.title = "TeleStore Manager"
    }
  }, [notifications, getUnreadNotificationsCount])

  // Questo componente non renderizza nulla, gestisce solo le notifiche
  return null
}

