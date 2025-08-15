import React from 'react';
import { Home, Search, Video, User, Plus } from 'lucide-react';
import { toast } from '@/hooks/use-toast';

const BottomNav: React.FC = () => {
  const handleUploadClick = () => {
    toast({
      title: "Upload",
      description: "Upload feature coming soon!",
      duration: 2000,
    });
  };

  return (
    <div className="fixed bottom-0 left-0 right-0 h-16 bg-black flex justify-around items-center border-t border-gray-800 z-20">
      <button className="flex flex-col items-center justify-center flex-1">
        <Home className="w-6 h-6 text-white" />
        <span className="text-xs mt-1 text-white">Home</span>
      </button>
      <button className="flex flex-col items-center justify-center flex-1">
        <Search className="w-6 h-6 text-tiktok-gray" />
        <span className="text-xs mt-1 text-tiktok-gray">Discover</span>
      </button>
      <button 
        className="rounded-full bg-gradient-to-r from-tiktok-cyan to-tiktok-pink p-[3px] -mt-5"
        onClick={handleUploadClick}
      >
        <div className="bg-black rounded-full p-1">
          <Plus className="w-6 h-6 text-white" />
        </div>
      </button>
      <button className="flex flex-col items-center justify-center flex-1">
        <Video className="w-6 h-6 text-tiktok-gray" />
        <span className="text-xs mt-1 text-tiktok-gray">Inbox</span>
      </button>
      <button className="flex flex-col items-center justify-center flex-1">
        <User className="w-6 h-6 text-tiktok-gray" />
        <span className="text-xs mt-1 text-tiktok-gray">Me</span>
      </button>
    </div>
  );
};

export default BottomNav;
import React from 'react';

const Header: React.FC = () => {
  return (
    <header className="fixed top-0 left-0 right-0 h-12 bg-transparent z-10 flex items-center justify-center">
      <div className="flex space-x-12 text-white font-medium">
        <div className="cursor-pointer opacity-60">Following</div>
        <div className="cursor-pointer border-b-2 border-white">For You</div>
      </div>
    </header>
  );
};

export default Header;
import React from 'react';
import { Heart, Share2, MessageCircle } from 'lucide-react';
import { VideoData } from '../data/videos';
import { useVideo } from '../context/VideoContext';
import { toast } from '@/hooks/use-toast';

interface VideoActionsProps {
  video: VideoData;
}

const VideoActions: React.FC<VideoActionsProps> = ({ video }) => {
  const { toggleLike, incrementShare } = useVideo();

  const formatCount = (count: number): string => {
    if (count >= 1000000) {
      return `${(count / 1000000).toFixed(1)}M`;
    }
    if (count >= 1000) {
      return `${(count / 1000).toFixed(1)}K`;
    }
    return count.toString();
  };

  const handleLikeClick = () => {
    toggleLike(video.id);
  };

  const handleCommentClick = () => {
    toast({
      title: "Comments",
      description: "Comments feature coming soon!",
      duration: 2000,
    });
  };

  const handleShareClick = () => {
    incrementShare(video.id);
    toast({
      title: "Shared!",
      description: "Video shared successfully",
      duration: 2000,
    });
  };

  return (
    <div className="absolute right-4 bottom-24 flex flex-col items-center space-y-6">
      <div className="flex flex-col items-center">
        <button 
          onClick={handleLikeClick}
          className="w-10 h-10 bg-black bg-opacity-50 rounded-full flex items-center justify-center"
        >
          <Heart 
            className={`w-6 h-6 ${video.liked ? 'text-tiktok-pink fill-tiktok-pink' : 'text-white'}`} 
          />
        </button>
        <span className="text-white text-xs mt-1">{formatCount(video.likes)}</span>
      </div>

      <div className="flex flex-col items-center">
        <button 
          onClick={handleCommentClick}
          className="w-10 h-10 bg-black bg-opacity-50 rounded-full flex items-center justify-center"
        >
          <MessageCircle className="w-6 h-6 text-white" />
        </button>
        <span className="text-white text-xs mt-1">{formatCount(video.comments)}</span>
      </div>

      <div className="flex flex-col items-center">
        <button 
          onClick={handleShareClick}
          className="w-10 h-10 bg-black bg-opacity-50 rounded-full flex items-center justify-center"
        >
          <Share2 className="w-6 h-6 text-white" />
        </button>
        <span className="text-white text-xs mt-1">{formatCount(video.shares)}</span>
      </div>

      <div className="flex flex-col items-center">
        <div className="w-10 h-10 overflow-hidden rounded-full border-2 border-tiktok-pink">
          <img 
            src={video.user.avatar} 
            alt={video.user.username} 
            className="w-full h-full object-cover"
          />
        </div>
      </div>
    </div>
  );
};

export default VideoActions;
import React, { useState, useEffect } from 'react';
import VideoPlayer from './VideoPlayer';
import VideoActions from './VideoActions';
import VideoInfo from './VideoInfo';
import { useVideo } from '../context/VideoContext';

const VideoFeed: React.FC = () => {
  const { videos, currentVideoIndex, setCurrentVideoIndex } = useVideo();
  const [touchStart, setTouchStart] = useState<number | null>(null);
  const [touchEnd, setTouchEnd] = useState<number | null>(null);

  // Handle scroll events to update the current video
  const handleScroll = (e: React.UIEvent<HTMLDivElement>) => {
    const container = e.currentTarget;
    const scrollPosition = container.scrollTop;
    const videoHeight = container.clientHeight;
    
    const index = Math.round(scrollPosition / videoHeight);
    if (index !== currentVideoIndex && index >= 0 && index < videos.length) {
      setCurrentVideoIndex(index);
    }
  };

  // Handle touch events for swipe navigation
  const handleTouchStart = (e: React.TouchEvent) => {
    setTouchStart(e.targetTouches[0].clientY);
  };

  const handleTouchMove = (e: React.TouchEvent) => {
    setTouchEnd(e.targetTouches[0].clientY);
  };

  const handleTouchEnd = () => {
    if (!touchStart || !touchEnd) return;
    
    const distance = touchStart - touchEnd;
    const isSignificantSwipe = Math.abs(distance) > 50;
    
    if (isSignificantSwipe) {
      if (distance > 0 && currentVideoIndex < videos.length - 1) {
        // Swipe up - go to next video
        setCurrentVideoIndex(currentVideoIndex + 1);
      } else if (distance < 0 && currentVideoIndex > 0) {
        // Swipe down - go to previous video
        setCurrentVideoIndex(currentVideoIndex - 1);
      }
    }
    
    setTouchStart(null);
    setTouchEnd(null);
  };

  // Scroll to the current video when currentVideoIndex changes
  useEffect(() => {
    const videoContainer = document.getElementById('video-container');
    if (videoContainer) {
      videoContainer.scrollTo({
        top: currentVideoIndex * videoContainer.clientHeight,
        behavior: 'smooth'
      });
    }
  }, [currentVideoIndex]);

  return (
    <div 
      id="video-container"
      className="video-snap-container"
      onScroll={handleScroll}
      onTouchStart={handleTouchStart}
      onTouchMove={handleTouchMove}
      onTouchEnd={handleTouchEnd}
    >
      {videos.map((video, index) => (
        <div key={video.id} className="video-snap-item">
          <VideoPlayer 
            videoUrl={video.videoUrl} 
            isActive={index === currentVideoIndex}
            onVideoClick={() => {}}
          />
          <VideoInfo video={video} />
          <VideoActions video={video} />
        </div>
      ))}
    </div>
  );
};

export default VideoFeed;
import React from 'react';
import { VideoData } from '../data/videos';
import { cn } from '@/lib/utils';

interface VideoInfoProps {
  video: VideoData;
  className?: string;
}

const VideoInfo: React.FC<VideoInfoProps> = ({ video, className }) => {
  return (
    <div className={cn("absolute bottom-20 left-4 max-w-[70%] text-white z-10", className)}>
      <div className="font-semibold text-lg">@{video.user.username}</div>
      <div className="text-sm mt-2">{video.description}</div>
    </div>
  );
};

export default VideoInfo;
import React, { useRef, useState, useEffect } from 'react';
import { Play } from 'lucide-react';

interface VideoPlayerProps {
  videoUrl: string;
  isActive: boolean;
  onVideoClick: () => void;
}

const VideoPlayer: React.FC<VideoPlayerProps> = ({ videoUrl, isActive, onVideoClick }) => {
  const videoRef = useRef<HTMLVideoElement>(null);
  const [isPlaying, setIsPlaying] = useState(false);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (videoRef.current) {
      if (isActive) {
        videoRef.current.play().catch(error => {
          console.error("Error playing video:", error);
        });
        setIsPlaying(true);
      } else {
        videoRef.current.pause();
        setIsPlaying(false);
      }
    }
  }, [isActive]);

  const handleVideoClick = () => {
    if (videoRef.current) {
      if (isPlaying) {
        videoRef.current.pause();
        setIsPlaying(false);
      } else {
        videoRef.current.play().catch(error => {
          console.error("Error playing video:", error);
        });
        setIsPlaying(true);
      }
    }
    onVideoClick();
  };

  const handleLoadedData = () => {
    setLoading(false);
    if (isActive && videoRef.current) {
      videoRef.current.play().catch(error => {
        console.error("Error playing video:", error);
      });
      setIsPlaying(true);
    }
  };

  return (
    <div className="relative w-full h-full" onClick={handleVideoClick}>
      {loading && (
        <div className="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30 z-10">
          <div className="w-8 h-8 border-4 border-tiktok-cyan border-t-tiktok-pink rounded-full animate-spin"></div>
        </div>
      )}
      
      {!isPlaying && !loading && (
        <div className="absolute inset-0 flex items-center justify-center z-10">
          <Play className="w-16 h-16 text-white opacity-80" />
        </div>
      )}
      
      <video
        ref={videoRef}
        src={videoUrl}
        className="object-cover w-full h-full"
        loop
        playsInline
        muted
        preload="auto"
        onLoadedData={handleLoadedData}
      />
    </div>
  );
};

export default VideoPlayer;
import React, { createContext, useContext, useState, ReactNode } from 'react';
import { VideoData, mockVideos } from '../data/videos';

interface VideoContextProps {
  videos: VideoData[];
  currentVideoIndex: number;
  setCurrentVideoIndex: (index: number) => void;
  toggleLike: (videoId: string) => void;
  incrementShare: (videoId: string) => void;
}

const VideoContext = createContext<VideoContextProps | undefined>(undefined);

export const VideoProvider: React.FC<{ children: ReactNode }> = ({ children }) => {
  const [videos, setVideos] = useState<VideoData[]>(mockVideos);
  const [currentVideoIndex, setCurrentVideoIndex] = useState(0);

  const toggleLike = (videoId: string) => {
    setVideos(prevVideos => 
      prevVideos.map(video => {
        if (video.id === videoId) {
          const liked = !video.liked;
          return {
            ...video,
            liked,
            likes: liked ? video.likes + 1 : video.likes - 1
          };
        }
        return video;
      })
    );
  };

  const incrementShare = (videoId: string) => {
    setVideos(prevVideos => 
      prevVideos.map(video => {
        if (video.id === videoId) {
          return {
            ...video,
            shares: video.shares + 1
          };
        }
        return video;
      })
    );
  };

  return (
    <VideoContext.Provider value={{ 
      videos, 
      currentVideoIndex, 
      setCurrentVideoIndex,
      toggleLike,
      incrementShare
    }}>
      {children}
    </VideoContext.Provider>
  );
};

export const useVideo = () => {
  const context = useContext(VideoContext);
  if (context === undefined) {
    throw new Error('useVideo must be used within a VideoProvider');
  }
  return context;
};
// Mock data for videos

export interface VideoData {
  id: string;
  videoUrl: string;
  user: {
    id: string;
    username: string;
    avatar: string;
  };
  description: string;
  likes: number;
  comments: number;
  shares: number;
  liked: boolean;
}

export const mockVideos: VideoData[] = [
  {
    id: "v1",
    videoUrl: "https://assets.mixkit.co/videos/preview/mixkit-woman-dancing-happily-1228-large.mp4",
    user: {
      id: "u1",
      username: "dancequeen",
      avatar: "https://i.pravatar.cc/150?img=1",
    },
    description: "Dancing to my favorite song! #dance #happy #vibes",
    likes: 1245,
    comments: 86,
    shares: 32,
    liked: false,
  },
  {
    id: "v2",
    videoUrl: "https://assets.mixkit.co/videos/preview/mixkit-man-dancing-under-changing-lights-1240-large.mp4",
    user: {
      id: "u2",
      username: "groovyking",
      avatar: "https://i.pravatar.cc/150?img=2",
    },
    description: "Late night vibes 🔥 #nightlife #dance #lights",
    likes: 2390,
    comments: 145,
    shares: 78,
    liked: false,
  },
  {
    id: "v3",
    videoUrl: "https://assets.mixkit.co/videos/preview/mixkit-mother-with-her-little-daughter-eating-a-marshmallow-in-nature-39764-large.mp4",
    user: {
      id: "u3",
      username: "familytime",
      avatar: "https://i.pravatar.cc/150?img=5",
    },
    description: "Sweet moments with my daughter ❤️ #family #love #outdoors",
    likes: 4521,
    comments: 212,
    shares: 121,
    liked: false,
  },
  {
    id: "v4",
    videoUrl: "https://assets.mixkit.co/videos/preview/mixkit-friends-with-colored-smoke-bombs-4556-large.mp4",
    user: {
      id: "u4",
      username: "adventuretime",
      avatar: "https://i.pravatar.cc/150?img=8",
    },
    description: "Weekend adventures with the squad! #friends #colorsmoke #fun",
    likes: 8701,
    comments: 342,
    shares: 201,
    liked: false,
  },
  {
    id: "v5",
    videoUrl: "https://assets.mixkit.co/videos/preview/mixkit-portrait-of-a-fashion-woman-with-silver-makeup-39875-large.mp4",
    user: {
      id: "u5",
      username: "glamqueen",
      avatar: "https://i.pravatar.cc/150?img=9",
    },
    description: "New makeup look for tonight ✨ #makeup #glam #silver",
    likes: 12034,
    comments: 534,
    shares: 267,
    liked: false,
  },
];
