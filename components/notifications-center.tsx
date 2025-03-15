"use client"

import { useState } from "react"
import { useStore } from "@/lib/store"
import { Button } from "@/components/ui/button"
import { Bell, X, Check, AlertTriangle, Info, CheckCircle } from "lucide-react"
import { Badge } from "@/components/ui/badge"
import { ScrollArea } from "@/components/ui/scroll-area"
import { cn } from "@/lib/utils"

export function NotificationsCenter() {
  const {
    notifications,
    markNotificationAsRead,
    markAllNotificationsAsRead,
    deleteNotification,
    getUnreadNotificationsCount,
  } = useStore()

  const [isOpen, setIsOpen] = useState(false)
  const unreadCount = getUnreadNotificationsCount()

  const getNotificationIcon = (type) => {
    switch (type) {
      case "success":
        return <CheckCircle className="h-4 w-4 text-green-500" />
      case "warning":
        return <AlertTriangle className="h-4 w-4 text-amber-500" />
      case "error":
        return <X className="h-4 w-4 text-red-500" />
      default:
        return <Info className="h-4 w-4 text-blue-500" />
    }
  }

  return (
    <div className="relative">
      <Button variant="outline" size="icon" className="relative" onClick={() => setIsOpen(!isOpen)}>
        <Bell className="h-5 w-5" />
        {unreadCount > 0 && (
          <Badge className="absolute -top-1 -right-1 h-5 w-5 rounded-full p-0 flex items-center justify-center bg-red-500">
            {unreadCount}
          </Badge>
        )}
      </Button>

      {isOpen && (
        <div className="absolute right-0 mt-2 w-80 bg-background rounded-md border shadow-lg z-50">
          <div className="p-4 border-b flex items-center justify-between">
            <div>
              <div className="font-medium">Notifiche</div>
              <div className="text-xs text-muted-foreground">{unreadCount} non lette</div>
            </div>
            {unreadCount > 0 && (
              <Button variant="ghost" size="sm" className="text-xs" onClick={() => markAllNotificationsAsRead()}>
                Segna tutte come lette
              </Button>
            )}
          </div>

          <ScrollArea className="max-h-[400px]">
            {notifications.length > 0 ? (
              <div>
                {notifications.map((notification) => (
                  <div
                    key={notification.id}
                    className={cn(
                      "p-4 border-b last:border-0 hover:bg-muted/30 transition-colors",
                      !notification.read && "bg-muted/50",
                    )}
                  >
                    <div className="flex items-start gap-3">
                      <div
                        className={cn(
                          "rounded-full p-1.5",
                          notification.type === "warning" && "bg-amber-100 dark:bg-amber-900/30",
                          notification.type === "success" && "bg-green-100 dark:bg-green-900/30",
                          notification.type === "error" && "bg-red-100 dark:bg-red-900/30",
                          notification.type === "info" && "bg-blue-100 dark:bg-blue-900/30",
                        )}
                      >
                        {getNotificationIcon(notification.type)}
                      </div>
                      <div className="flex-1">
                        <div className="text-sm font-medium">{notification.title}</div>
                        <div className="text-xs text-muted-foreground">{notification.message}</div>
                        <div className="text-xs text-muted-foreground mt-1">
                          {new Date(notification.date).toLocaleString()}
                        </div>
                      </div>
                      <div className="flex flex-col gap-1">
                        {!notification.read && (
                          <Button
                            variant="ghost"
                            size="icon"
                            className="h-6 w-6"
                            onClick={() => markNotificationAsRead(notification.id)}
                          >
                            <Check className="h-3 w-3" />
                            <span className="sr-only">Segna come letta</span>
                          </Button>
                        )}
                        <Button
                          variant="ghost"
                          size="icon"
                          className="h-6 w-6"
                          onClick={() => deleteNotification(notification.id)}
                        >
                          <X className="h-3 w-3" />
                          <span className="sr-only">Elimina</span>
                        </Button>
                      </div>
                    </div>
                  </div>
                ))}
              </div>
            ) : (
              <div className="p-4 text-center text-muted-foreground">Nessuna notifica</div>
            )}
          </ScrollArea>

          <div className="p-2 border-t">
            <Button variant="ghost" className="w-full text-xs" onClick={() => setIsOpen(false)}>
              Chiudi
            </Button>
          </div>
        </div>
      )}
    </div>
  )
}

